<?php

declare(strict_types=1);

use App\Controller\HomeController;
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

require dirname(__DIR__) . '/vendor/autoload.php';

date_default_timezone_set('Asia/Manila');

$projectDir = dirname(__DIR__);
if (file_exists($projectDir . '/.env')) {
    (new Dotenv())->bootEnv($projectDir . '/.env');
}

$_SERVER['APP_ENV'] = $_SERVER['APP_ENV'] ?? 'dev';
$_SERVER['APP_DEBUG'] = $_SERVER['APP_DEBUG'] ?? '1';

class HomeControllerProbe2 extends HomeController
{
    public array $captured = [];

    public function render(string $view, array $parameters = [], ?Response $response = null): Response
    {
        $this->captured = ['view' => $view, 'parameters' => $parameters];
        return $response ?? new Response('probe');
    }
}

$kernel = new App\Kernel('dev', true);
$kernel->boot();
$container = $kernel->getContainer();
$em = $container->get('doctrine')->getManager();

/** @var UserRepository $userRepo */
$userRepo = $em->getRepository(User::class);
/** @var EvaluationPeriodRepository $evalRepo */
$evalRepo = $em->getRepository(\App\Entity\EvaluationPeriod::class);
/** @var EvaluationResponseRepository $responseRepo */
$responseRepo = $em->getRepository(\App\Entity\EvaluationResponse::class);
/** @var SubjectRepository $subjectRepo */
$subjectRepo = $em->getRepository(\App\Entity\Subject::class);
/** @var CurriculumRepository $curriculumRepo */
$curriculumRepo = $em->getRepository(\App\Entity\Curriculum::class);

$student = $userRepo->find(77);
if (!$student instanceof User) {
    echo "RESULT=BLOCKED\n";
    echo "ERROR=Student id 77 not found\n";
    $kernel->shutdown();
    exit(0);
}

$request = Request::create('/dashboard', 'GET');
$session = new Session(new MockArraySessionStorage());
$session->start();
$session->set('student_loadslip_verified', true);
$session->set('student_loadslip_student_number', '202000480');
$session->set('student_loadslip_codes', ['ITS 403','ITS 404','ITS 405']);
$session->set('student_loadslip_rows', [
    ['code'=>'ITS 403','section'=>'C','description'=>'CAPSTONE PROJECT 2','schedule'=>'TH-F 8:30-10:00 AM','units'=>'3'],
    ['code'=>'ITS 404','section'=>'C','description'=>'SOCIAL AND PROFESSIONAL ISSUES OF IT','schedule'=>'TH-F 7:00-8:30 AM','units'=>'3'],
    ['code'=>'ITS 405','section'=>'C','description'=>'MULTIMEDIA SYSTEMS','schedule'=>'TH-F 10:00-11:30 AM','units'=>'3'],
]);
$request->setSession($session);

$controller = new HomeControllerProbe2();
$controller->setContainer($container);

$method = new ReflectionMethod($controller, 'studentDashboard');
$method->setAccessible(true);
$method->invoke(
    $controller,
    $request,
    $student,
    $curriculumRepo,
    $evalRepo,
    $responseRepo,
    $userRepo,
    $subjectRepo,
);

$pending = (array) (($controller->captured['parameters']['pending'] ?? []) ?: []);
$completed = (array) (($controller->captured['parameters']['completed'] ?? []) ?: []);

$open = $evalRepo->findOpen('SET');
echo 'OPEN_SET=' . count($open) . PHP_EOL;
echo 'PENDING=' . count($pending) . PHP_EOL;
echo 'COMPLETED=' . count($completed) . PHP_EOL;
foreach ($pending as $item) {
    $ev = $item['evaluation'];
    $sub = $item['subject'];
    $row = $item['loadslipRow'] ?? [];
    echo 'PENDING_ITEM evalId=' . $ev->getId() . ' subject=' . $sub->getSubjectCode() . ' faculty=' . $item['faculty']->getFullName() . ' rowSchedule=' . ($row['schedule'] ?? '-') . PHP_EOL;
}

echo (count($pending) > 0 ? 'RESULT=PASS' : 'RESULT=FAIL') . PHP_EOL;

$kernel->shutdown();
