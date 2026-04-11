<?php

declare(strict_types=1);

use App\Controller\HomeController;
use App\Entity\EvaluationPeriod;
use App\Entity\Subject;
use App\Entity\User;
use App\Repository\CurriculumRepository;
use App\Repository\EvaluationPeriodRepository;
use App\Repository\EvaluationResponseRepository;
use App\Repository\SubjectRepository;
use App\Repository\UserRepository;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

error_reporting(E_ALL);
ini_set('display_errors', '1');

require dirname(__DIR__) . '/vendor/autoload.php';

class HomeControllerProbe extends HomeController
{
    public array $captured = [];

    public function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        $this->captured = [
            'view' => $view,
            'parameters' => $parameters,
        ];

        return $response ?? new Response('probe');
    }
}

$projectDir = dirname(__DIR__);
if (file_exists($projectDir . '/.env')) {
    (new Dotenv())->bootEnv($projectDir . '/.env');
}

$_SERVER['APP_ENV'] = $_SERVER['APP_ENV'] ?? 'dev';
$_SERVER['APP_DEBUG'] = $_SERVER['APP_DEBUG'] ?? '1';

$kernel = new App\Kernel('dev', true);
$kernel->boot();
$container = $kernel->getContainer();
$em = $container->get('doctrine')->getManager();

/** @var UserRepository $userRepo */
$userRepo = $em->getRepository(User::class);
/** @var SubjectRepository $subjectRepo */
$subjectRepo = $em->getRepository(Subject::class);
/** @var EvaluationPeriodRepository $evalRepo */
$evalRepo = $em->getRepository(EvaluationPeriod::class);
/** @var EvaluationResponseRepository $responseRepo */
$responseRepo = $em->getRepository(\App\Entity\EvaluationResponse::class);
/** @var CurriculumRepository $curriculumRepo */
$curriculumRepo = $em->getRepository(\App\Entity\Curriculum::class);

$normalizeCode = static function (?string $value): string {
    $raw = strtoupper(trim((string) $value));
    return (string) preg_replace('/[^A-Z0-9]/u', '', $raw);
};

$normalizeStudentNo = static function (?string $value): string {
    $raw = strtoupper(trim((string) $value));
    $raw = str_replace(
        ['O', 'Q', 'D', 'I', 'L', '|', '!', 'S', 'B', 'Z', 'G'],
        ['0', '0', '0', '1', '1', '1', '1', '5', '8', '2', '6'],
        $raw
    );
    return (string) preg_replace('/[^0-9]/u', '', $raw);
};

$extractNormalizedSubjectCode = static function (?string $label) use ($normalizeCode): string {
    $raw = trim((string) $label);
    if ($raw === '') {
        return '';
    }

    $parts = preg_split('/\s*[—-]\s*/u', $raw, 2);
    $primary = trim((string) ($parts[0] ?? ''));
    $primaryCode = $normalizeCode($primary);
    if ($primaryCode !== '') {
        return $primaryCode;
    }

    if (preg_match('/^\s*([A-Za-z]{2,}\s*\d+[A-Za-z0-9]*)/u', $raw, $matches) === 1) {
        return $normalizeCode((string) ($matches[1] ?? ''));
    }

    return '';
};

$subjectsByNormalizedCode = [];
foreach ($subjectRepo->findAll() as $knownSubject) {
    $knownCode = $normalizeCode((string) $knownSubject->getSubjectCode());
    if ($knownCode !== '' && !isset($subjectsByNormalizedCode[$knownCode])) {
        $subjectsByNormalizedCode[$knownCode] = $knownSubject;
    }
}

$student = null;
$row = null;
$subject = null;
$faculty = null;

$verificationFiles = glob($projectDir . '/var/loadslip-verifications/*.json') ?: [];
foreach ($verificationFiles as $filePath) {
    $json = @file_get_contents($filePath);
    if (!is_string($json) || $json === '') {
        continue;
    }

    $data = json_decode($json, true);
    if (!is_array($data)) {
        continue;
    }

    $studentNo = $normalizeStudentNo((string) ($data['studentNumber'] ?? ''));
    if ($studentNo === '') {
        continue;
    }

    $candidate = $userRepo->findOneBy(['schoolId' => $studentNo]);
    if (!$candidate instanceof User || !$candidate->isStudent()) {
        continue;
    }

    $rows = is_array($data['rows'] ?? null) ? $data['rows'] : [];
    foreach ($rows as $candidateRow) {
        if (!is_array($candidateRow)) {
            continue;
        }

        $codeRaw = trim((string) ($candidateRow['code'] ?? ''));
        $scheduleRaw = trim((string) ($candidateRow['schedule'] ?? ''));
        if ($codeRaw === '' || $scheduleRaw === '') {
            continue;
        }

        $norm = $normalizeCode($codeRaw);
        if ($norm === '' || !isset($subjectsByNormalizedCode[$norm])) {
            continue;
        }

        $candidateSubject = $subjectsByNormalizedCode[$norm];
        $candidateFaculty = $candidateSubject->getFaculty();
        if (!$candidateFaculty instanceof User) {
            continue;
        }

        $student = $candidate;
        $subject = $candidateSubject;
        $faculty = $candidateFaculty;
        $row = [
            'code' => $codeRaw,
            'section' => strtoupper(trim((string) ($candidateRow['section'] ?? ''))),
            'description' => trim((string) ($candidateRow['description'] ?? '')),
            'schedule' => $scheduleRaw,
            'units' => trim((string) ($candidateRow['units'] ?? '')),
        ];
        break 2;
    }
}

if (!$student || !$subject || !$faculty || !$row) {
    echo "RESULT=BLOCKED\n";
    echo "ERROR=No verified student row with mapped subject+faculty found\n";
    $kernel->shutdown();
    exit(0);
}

$controller = new HomeControllerProbe();
$controller->setContainer($container);

$invokePrivate = static function (object $instance, string $methodName, array $args = []) {
    $method = new ReflectionMethod($instance, $methodName);
    $method->setAccessible(true);
    return $method->invokeArgs($instance, $args);
};

$normalizeSchedule = static function (HomeController $c, string $value) use ($invokePrivate): string {
    return (string) $invokePrivate($c, 'normalizeDashboardScheduleValue', [$value]);
};

$schedulesCompatible = static function (HomeController $c, string $target, string $rowSchedule) use ($invokePrivate): bool {
    return (bool) $invokePrivate($c, 'dashboardSchedulesAreCompatible', [$target, $rowSchedule]);
};

$token = 'DASHGATE_' . date('Ymd_His');

$matchEval = new EvaluationPeriod();
$matchEval->setEvaluationType('SET');
$matchEval->setSchoolYear('MATCH_' . $token);
$matchEval->setStatus(true);
$matchEval->setStartDate(new DateTime('-1 hour'));
$matchEval->setEndDate(new DateTime('+1 hour'));
$matchEval->setFaculty(trim($faculty->getFullName()));
$matchEval->setSubject((string) $subject->getSubjectCode());
$matchEval->setSection((string) ($row['section'] ?? ''));
$matchEval->setTime((string) ($row['schedule'] ?? ''));

$mismatchEval = new EvaluationPeriod();
$mismatchEval->setEvaluationType('SET');
$mismatchEval->setSchoolYear('MISMATCH_' . $token);
$mismatchEval->setStatus(true);
$mismatchEval->setStartDate(new DateTime('-1 hour'));
$mismatchEval->setEndDate(new DateTime('+1 hour'));
$mismatchEval->setFaculty(trim($faculty->getFullName()));
$mismatchEval->setSubject((string) $subject->getSubjectCode());
$mismatchEval->setSection((string) ($row['section'] ?? ''));
$mismatchEval->setTime('M 01:00-02:00 PM');

$em->persist($matchEval);
$em->persist($mismatchEval);
$em->flush();

try {
    $request = Request::create('/dashboard', 'GET');
    $session = new Session(new MockArraySessionStorage());
    $session->start();

    $session->set('student_loadslip_verified', true);
    $session->set('student_loadslip_student_number', $normalizeStudentNo((string) $student->getSchoolId()));
    $session->set('student_loadslip_codes', [(string) $row['code']]);
    $session->set('student_loadslip_rows', [$row]);
    $request->setSession($session);

    $invokePrivate(
        $controller,
        'studentDashboard',
        [
            $request,
            $student,
            $curriculumRepo,
            $evalRepo,
            $responseRepo,
            $userRepo,
            $subjectRepo,
        ]
    );

    $pending = (array) (($controller->captured['parameters']['pending'] ?? []) ?: []);
    $completed = (array) (($controller->captured['parameters']['completed'] ?? []) ?: []);
    $visibleIds = [];
    foreach (array_merge($pending, $completed) as $item) {
        if (!is_array($item)) {
            continue;
        }
        $ev = $item['evaluation'] ?? null;
        if ($ev instanceof EvaluationPeriod && $ev->getId() !== null) {
            $visibleIds[(int) $ev->getId()] = true;
        }
    }

    $matchVisible = isset($visibleIds[(int) $matchEval->getId()]);
    $mismatchVisible = isset($visibleIds[(int) $mismatchEval->getId()]);

    $openSetIds = array_map(static fn($e) => $e->getId(), $evalRepo->findOpen('SET'));

    $loadslipCode = $normalizeCode((string) $row['code']);
    $loadslipCodes = [$loadslipCode => true];
    $candidateRows = [$loadslipCode => [$row]];

    $evaluateReason = static function (EvaluationPeriod $eval) use (
        $student,
        $subjectRepo,
        $userRepo,
        $normalizeCode,
        $extractNormalizedSubjectCode,
        $loadslipCodes,
        $candidateRows,
        $controller,
        $normalizeSchedule,
        $schedulesCompatible,
        $subjectsByNormalizedCode
    ): array {
        if ($eval->getEvaluationType() !== 'SET') {
            return ['pass' => false, 'reason' => 'not_set'];
        }

        if ($eval->getYearLevel() !== null) {
            // In this test we leave year null; keep check for completeness.
            $studentYL = trim((string) ($student->getYearLevel() ?? ''));
            $evalYL = trim((string) ($eval->getYearLevel() ?? ''));
            if ($studentYL !== '' && $evalYL !== '' && strcasecmp($studentYL, $evalYL) !== 0) {
                return ['pass' => false, 'reason' => 'year_filter'];
            }
        }

        if ($eval->getDepartment() !== null) {
            $studentDept = $student->getDepartment()?->getId();
            $evalDept = $eval->getDepartment()?->getId();
            if (!$studentDept || !$evalDept || $studentDept !== $evalDept) {
                return ['pass' => false, 'reason' => 'dept_filter'];
            }
        }

        if ($eval->getCollege() !== null) {
            $studentCollege = $student->getDepartment()?->getCollegeName();
            if (!$studentCollege || $studentCollege !== $eval->getCollege()) {
                return ['pass' => false, 'reason' => 'college_filter'];
            }
        }

        $subject = null;
        $faculty = null;
        $subjectLabel = trim((string) ($eval->getSubject() ?? ''));

        $normalizedEvalCode = $extractNormalizedSubjectCode($subjectLabel);
        if ($normalizedEvalCode !== '' && isset($subjectsByNormalizedCode[$normalizedEvalCode])) {
            $subject = $subjectsByNormalizedCode[$normalizedEvalCode];
        }

        if (!$subject && $subjectLabel !== '') {
            $parts = preg_split('/\s*[—-]\s*/u', $subjectLabel, 2);
            $part = trim((string) ($parts[0] ?? ''));
            $partNorm = $normalizeCode($part);
            if ($partNorm !== '' && isset($subjectsByNormalizedCode[$partNorm])) {
                $subject = $subjectsByNormalizedCode[$partNorm];
            } elseif ($part !== '') {
                $subject = $subjectRepo->findOneBy(['subjectCode' => $part]);
            }
        }

        if ($subject instanceof Subject) {
            $faculty = $subject->getFaculty();
        }

        if (!$faculty && $eval->getFaculty()) {
            $faculty = $userRepo->findOneByFullName(trim((string) $eval->getFaculty()));
        }

        if (!$subject || !$faculty) {
            return ['pass' => false, 'reason' => 'subject_or_faculty_missing'];
        }

        $subjectCode = $normalizeCode((string) $subject->getSubjectCode());
        if (!isset($loadslipCodes[$subjectCode])) {
            return ['pass' => false, 'reason' => 'code_filter'];
        }

        $evalSection = strtoupper(trim((string) ($eval->getSection() ?? '')));
        $evalScheduleNorm = $normalizeSchedule($controller, (string) ($eval->getTime() ?? ''));
        $rows = $candidateRows[$subjectCode] ?? [];

        $best = null;
        foreach ($rows as $candidate) {
            $rowSection = strtoupper(trim((string) ($candidate['section'] ?? '')));
            $rowScheduleNorm = $normalizeSchedule($controller, (string) ($candidate['schedule'] ?? ''));

            if ($evalSection !== '' && $rowSection !== '' && $rowSection !== $evalSection) {
                continue;
            }

            if ($evalScheduleNorm !== '') {
                if ($rowScheduleNorm === '' || !$schedulesCompatible($controller, $evalScheduleNorm, $rowScheduleNorm)) {
                    continue;
                }
            }

            $best = $candidate;
            break;
        }

        if ($best === null) {
            return ['pass' => false, 'reason' => 'schedule_or_section_filter'];
        }

        return ['pass' => true, 'reason' => 'eligible'];
    };

    $matchDecision = $evaluateReason($matchEval);
    $mismatchDecision = $evaluateReason($mismatchEval);

    echo 'STUDENT=' . $student->getSchoolId() . PHP_EOL;
    echo 'ROW_CODE=' . $row['code'] . PHP_EOL;
    echo 'ROW_SECTION=' . ($row['section'] ?: '-') . PHP_EOL;
    echo 'ROW_SCHEDULE=' . $row['schedule'] . PHP_EOL;
    echo 'MATCH_EVAL_ID=' . $matchEval->getId() . PHP_EOL;
    echo 'MISMATCH_EVAL_ID=' . $mismatchEval->getId() . PHP_EOL;
    echo 'MATCH_IN_OPEN=' . (in_array($matchEval->getId(), $openSetIds, true) ? '1' : '0') . PHP_EOL;
    echo 'MISMATCH_IN_OPEN=' . (in_array($mismatchEval->getId(), $openSetIds, true) ? '1' : '0') . PHP_EOL;
    echo 'PENDING_COUNT=' . count($pending) . PHP_EOL;
    echo 'COMPLETED_COUNT=' . count($completed) . PHP_EOL;
    echo 'MATCH_VISIBLE=' . ($matchVisible ? '1' : '0') . PHP_EOL;
    echo 'MISMATCH_VISIBLE=' . ($mismatchVisible ? '1' : '0') . PHP_EOL;
    echo 'MATCH_MANUAL_PASS=' . ($matchDecision['pass'] ? '1' : '0') . PHP_EOL;
    echo 'MATCH_MANUAL_REASON=' . $matchDecision['reason'] . PHP_EOL;
    echo 'MISMATCH_MANUAL_PASS=' . ($mismatchDecision['pass'] ? '1' : '0') . PHP_EOL;
    echo 'MISMATCH_MANUAL_REASON=' . $mismatchDecision['reason'] . PHP_EOL;

    $result = ($matchVisible && !$mismatchVisible && $matchDecision['pass'] && !$mismatchDecision['pass']);
    echo 'RESULT=' . ($result ? 'PASS' : 'FAIL') . PHP_EOL;
} finally {
    if ($em->contains($matchEval)) {
        $em->remove($matchEval);
    }
    if ($em->contains($mismatchEval)) {
        $em->remove($mismatchEval);
    }
    $em->flush();
    $kernel->shutdown();
}
