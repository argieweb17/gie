<?php

declare(strict_types=1);

/**
 * Generic curriculum import script.
 *
 * Usage:
 *   php scripts/import_curriculum.php --file=path/to/curriculum.json [--dry-run]
 *
 * JSON format:
 * {
 *   "curriculumId": 7,
 *   "curriculumName": "BSIT Curriculum 2026",
 *   "curriculumYear": "2026",
 *   "departmentId": 1,
 *   "departmentName": "Information Technology",
 *   "collegeName": "College of Computer Studies",
 *   "courseId": null,
 *   "description": null,
 *   "schoolYear": "2026",
 *   "replaceLinks": true,
 *   "replaceSubjects": false,
 *   "subjects": [
 *     {
 *       "year": "First Year",
 *       "sem": "1st Semester",
 *       "code": "ITS 100",
 *       "name": "Introduction to Computing",
 *       "units": 3,
 *       "term": null,
 *       "section": null,
 *       "room": null,
 *       "schedule": null
 *     }
 *   ]
 * }
 */

require dirname(__DIR__) . '/vendor/autoload.php';

function stderr(string $message): void
{
    fwrite(STDERR, $message . PHP_EOL);
}

function stdout(string $message = ''): void
{
    fwrite(STDOUT, $message . PHP_EOL);
}

function printUsage(): void
{
    stdout('Curriculum Import');
    stdout('');
    stdout('Usage:');
    stdout('  php scripts/import_curriculum.php --file=path/to/curriculum.json [--dry-run]');
    stdout('');
    stdout('Options:');
    stdout('  --file     Path to the curriculum JSON payload');
    stdout('  --dry-run  Validate and preview inserts without writing to the database');
    stdout('  --help     Show this help message');
    stdout('');
    stdout('See scripts/curriculum_import.sample.json for a template.');
}

function requiredString(array $row, string $key, int $index): string
{
    $value = trim((string) ($row[$key] ?? ''));
    if ($value === '') {
        throw new RuntimeException(sprintf('Subject #%d is missing required field "%s".', $index + 1, $key));
    }

    return $value;
}

function optionalString(array $row, string $key): ?string
{
    if (!array_key_exists($key, $row) || $row[$key] === null) {
        return null;
    }

    $value = trim((string) $row[$key]);
    return $value === '' ? null : $value;
}

function normalizeUnits(array $row, int $index): ?int
{
    if (!array_key_exists('units', $row) || $row['units'] === null || $row['units'] === '') {
        return null;
    }

    if (!is_numeric($row['units'])) {
        throw new RuntimeException(sprintf('Subject #%d has a non-numeric "units" value.', $index + 1));
    }

    return (int) $row['units'];
}

$options = getopt('', ['file:', 'dry-run', 'help']);

if (isset($options['help'])) {
    printUsage();
    exit(0);
}

$filePath = $options['file'] ?? null;
if (!is_string($filePath) || trim($filePath) === '') {
    printUsage();
    exit(1);
}

$resolvedFilePath = $filePath;
if (!preg_match('/^[A-Za-z]:\\\\|^\\\\|^\//', $resolvedFilePath)) {
    $resolvedFilePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $resolvedFilePath);
}

if (!is_file($resolvedFilePath)) {
    stderr(sprintf('Curriculum file not found: %s', $resolvedFilePath));
    exit(1);
}

$payloadRaw = file_get_contents($resolvedFilePath);
if ($payloadRaw === false) {
    stderr(sprintf('Unable to read curriculum file: %s', $resolvedFilePath));
    exit(1);
}

try {
    $payload = json_decode($payloadRaw, true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $exception) {
    stderr(sprintf('Invalid JSON in %s: %s', $resolvedFilePath, $exception->getMessage()));
    exit(1);
}

if (!is_array($payload)) {
    stderr('Curriculum payload must decode to a JSON object.');
    exit(1);
}

$curriculumId = isset($payload['curriculumId']) && is_numeric($payload['curriculumId'])
    ? (int) $payload['curriculumId']
    : 0;
$curriculumName = trim((string) ($payload['curriculumName'] ?? ''));
$curriculumYear = optionalString($payload, 'curriculumYear');
$departmentId = isset($payload['departmentId']) && is_numeric($payload['departmentId'])
    ? (int) $payload['departmentId']
    : 0;
$departmentName = trim((string) ($payload['departmentName'] ?? ''));
$collegeName = optionalString($payload, 'collegeName');
$courseId = isset($payload['courseId']) && is_numeric($payload['courseId'])
    ? (int) $payload['courseId']
    : null;
$description = optionalString($payload, 'description');
$schoolYear = trim((string) ($payload['schoolYear'] ?? '2026'));
$replaceLinks = !array_key_exists('replaceLinks', $payload) || (bool) $payload['replaceLinks'];
$replaceSubjects = array_key_exists('replaceSubjects', $payload) && (bool) $payload['replaceSubjects'];
$subjects = $payload['subjects'] ?? null;

if ($curriculumId <= 0) {
    stderr('Payload must include a valid numeric "curriculumId".');
    exit(1);
}

if ($departmentId <= 0) {
    stderr('Payload must include a valid numeric "departmentId".');
    exit(1);
}

if ($schoolYear === '') {
    stderr('Payload must include a non-empty "schoolYear".');
    exit(1);
}

if ($replaceSubjects && !$replaceLinks) {
    stderr('Payload option "replaceSubjects" requires "replaceLinks" to be true.');
    exit(1);
}

if (!is_array($subjects) || $subjects === []) {
    stderr('Payload must include a non-empty "subjects" array.');
    exit(1);
}

$normalizedSubjects = [];
$totalUnits = 0;

foreach ($subjects as $index => $subject) {
    if (!is_array($subject)) {
        stderr(sprintf('Subject #%d must be an object.', $index + 1));
        exit(1);
    }

    try {
        $normalizedSubject = [
            'code' => requiredString($subject, 'code', $index),
            'name' => requiredString($subject, 'name', $index),
            'semester' => requiredString($subject, 'sem', $index),
            'yearLevel' => requiredString($subject, 'year', $index),
            'units' => normalizeUnits($subject, $index),
            'term' => optionalString($subject, 'term'),
            'section' => optionalString($subject, 'section'),
            'room' => optionalString($subject, 'room'),
            'schedule' => optionalString($subject, 'schedule'),
        ];
    } catch (RuntimeException $exception) {
        stderr($exception->getMessage());
        exit(1);
    }

    $normalizedSubjects[] = $normalizedSubject;
    $totalUnits += $normalizedSubject['units'] ?? 0;
}

$dryRun = isset($options['dry-run']);

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    $curriculum = $pdo->prepare('SELECT id, curriculum_name, department_id FROM curriculum WHERE id = :id');
    $curriculum->execute(['id' => $curriculumId]);
    $curriculumRow = $curriculum->fetch();

    $linkedSubjectIds = [];
    $deletableLinkedSubjectIds = [];
    if ($replaceSubjects) {
        $linkedSubjectQuery = $pdo->prepare(
            'SELECT DISTINCT subject_id FROM curriculum_subject WHERE curriculum_id = :curriculumId ORDER BY subject_id'
        );
        $linkedSubjectQuery->execute(['curriculumId' => $curriculumId]);
        $linkedSubjectIds = array_map(
            static fn (array $row): int => (int) $row['subject_id'],
            $linkedSubjectQuery->fetchAll()
        );

        if ($linkedSubjectIds !== []) {
            $exclusiveLinkedSubjectQuery = $pdo->prepare(
                'SELECT DISTINCT cs.subject_id '
                . 'FROM curriculum_subject cs '
                . 'WHERE cs.curriculum_id = :curriculumId '
                . 'AND NOT EXISTS ('
                . '    SELECT 1 FROM curriculum_subject other '
                . '    WHERE other.subject_id = cs.subject_id AND other.curriculum_id <> :curriculumId'
                . ') '
                . 'ORDER BY cs.subject_id'
            );
            $exclusiveLinkedSubjectQuery->execute(['curriculumId' => $curriculumId]);
            $deletableLinkedSubjectIds = array_map(
                static fn (array $row): int => (int) $row['subject_id'],
                $exclusiveLinkedSubjectQuery->fetchAll()
            );
        }
    }

    $department = $pdo->prepare('SELECT id, department_name, college_name FROM department WHERE id = :id');
    $department->execute(['id' => $departmentId]);
    $departmentRow = $department->fetch();

    $willCreateDepartment = false;
    $willUpdateDepartment = false;
    $departmentUpdateValues = [];
    if (!$departmentRow) {
        if ($departmentName === '') {
            throw new RuntimeException(sprintf(
                'Department with ID %d does not exist. Provide "departmentName" in the payload to create it.',
                $departmentId
            ));
        }

        $willCreateDepartment = true;
        $departmentRow = [
            'id' => $departmentId,
            'department_name' => $departmentName,
            'college_name' => $collegeName,
        ];
    } else {
        if ($departmentName !== '' && $departmentName !== (string) $departmentRow['department_name']) {
            $willUpdateDepartment = true;
            $departmentUpdateValues['department_name'] = $departmentName;
        }

        $currentCollegeName = $departmentRow['college_name'] !== null
            ? trim((string) $departmentRow['college_name'])
            : null;

        if ($collegeName !== $currentCollegeName) {
            $willUpdateDepartment = true;
            $departmentUpdateValues['college_name'] = $collegeName;
        }
    }

    $willCreateCurriculum = false;
    if (!$curriculumRow) {
        if ($curriculumName === '') {
            throw new RuntimeException(sprintf(
                'Curriculum with ID %d does not exist. Provide "curriculumName" in the payload to create it.',
                $curriculumId
            ));
        }

        $willCreateCurriculum = true;
        $curriculumRow = [
            'id' => $curriculumId,
            'curriculum_name' => $curriculumName,
            'department_id' => $departmentId,
        ];
    }

    stdout('=== Curriculum Import Preview ===');
    stdout(sprintf('Curriculum: %s (ID: %d)', (string) $curriculumRow['curriculum_name'], $curriculumId));
    stdout(sprintf('Department: %s (ID: %d)', (string) $departmentRow['department_name'], $departmentId));
    stdout(sprintf('School year: %s', $schoolYear));
    stdout(sprintf('Subjects: %d', count($normalizedSubjects)));
    stdout(sprintf('Total units: %d', $totalUnits));
    stdout(sprintf('Create department if missing: %s', $willCreateDepartment ? 'YES' : 'NO'));
    stdout(sprintf('Update department metadata: %s', $willUpdateDepartment ? 'YES' : 'NO'));
    stdout(sprintf('Create curriculum if missing: %s', $willCreateCurriculum ? 'YES' : 'NO'));
    stdout(sprintf('Replace existing curriculum links: %s', $replaceLinks ? 'YES' : 'NO'));
    stdout(sprintf('Replace previously linked subject rows: %s', $replaceSubjects ? 'YES' : 'NO'));

    if ($dryRun) {
        stdout('');
        stdout('[DRY RUN] No database changes were made.');
        if ($willCreateDepartment) {
            stdout(sprintf(
                '  Would create department "%s" (ID: %d, college=%s)',
                $departmentName,
                $departmentId,
                $collegeName ?? 'NULL'
            ));
        } elseif ($willUpdateDepartment) {
            stdout(sprintf(
                '  Would update department "%s" (ID: %d) metadata',
                (string) ($departmentUpdateValues['department_name'] ?? $departmentRow['department_name']),
                $departmentId
            ));
        }
        if ($willCreateCurriculum) {
            stdout(sprintf(
                '  Would create curriculum "%s" (ID: %d, year=%s)',
                $curriculumName,
                $curriculumId,
                $curriculumYear ?? 'NULL'
            ));
        }
        if ($replaceSubjects) {
            stdout(sprintf(
                '  Would delete %d previously linked subject row(s) and keep %d shared row(s)',
                count($deletableLinkedSubjectIds),
                count($linkedSubjectIds) - count($deletableLinkedSubjectIds)
            ));
        }
        foreach ($normalizedSubjects as $index => $subject) {
            stdout(sprintf(
                '  [%d] %s - %s | %s | %s | units=%s',
                $index + 1,
                $subject['code'],
                $subject['name'],
                $subject['yearLevel'],
                $subject['semester'],
                $subject['units'] ?? 'NULL'
            ));
        }
        exit(0);
    }

    $pdo->beginTransaction();

    if ($willCreateDepartment) {
        $insertDepartment = $pdo->prepare(
            'INSERT INTO department (id, department_name, college_name) VALUES (:id, :name, :collegeName)'
        );
        $insertDepartment->execute([
            'id' => $departmentId,
            'name' => $departmentName,
            'collegeName' => $collegeName,
        ]);
        stdout(sprintf('  Created department %s (ID: %d)', $departmentName, $departmentId));
    } elseif ($willUpdateDepartment) {
        $updateDepartment = $pdo->prepare(
            'UPDATE department SET department_name = :departmentName, college_name = :collegeName WHERE id = :id'
        );
        $updateDepartment->execute([
            'departmentName' => $departmentUpdateValues['department_name'] ?? $departmentRow['department_name'],
            'collegeName' => array_key_exists('college_name', $departmentUpdateValues)
                ? $departmentUpdateValues['college_name']
                : $departmentRow['college_name'],
            'id' => $departmentId,
        ]);
        stdout(sprintf('  Updated department %s (ID: %d)', (string) ($departmentUpdateValues['department_name'] ?? $departmentRow['department_name']), $departmentId));
    }

    if ($willCreateCurriculum) {
        $insertCurriculum = $pdo->prepare(
            'INSERT INTO curriculum (id, curriculum_name, curriculum_year, course_id, department_id, description) '
            . 'VALUES (:id, :name, :year, :courseId, :departmentId, :description)'
        );
        $insertCurriculum->execute([
            'id' => $curriculumId,
            'name' => $curriculumName,
            'year' => $curriculumYear,
            'courseId' => $courseId,
            'departmentId' => $departmentId,
            'description' => $description,
        ]);
        stdout(sprintf('  Created curriculum %s (ID: %d)', $curriculumName, $curriculumId));
    }

    if ($replaceLinks) {
        $deleteLinks = $pdo->prepare('DELETE FROM curriculum_subject WHERE curriculum_id = :curriculumId');
        $deleteLinks->execute(['curriculumId' => $curriculumId]);
    }

    if ($replaceSubjects && $deletableLinkedSubjectIds !== []) {
        $placeholders = implode(', ', array_fill(0, count($deletableLinkedSubjectIds), '?'));
        $deleteSubjects = $pdo->prepare(sprintf('DELETE FROM subject WHERE id IN (%s)', $placeholders));
        $deleteSubjects->execute($deletableLinkedSubjectIds);
        stdout(sprintf('  Deleted %d previously linked subject row(s)', count($deletableLinkedSubjectIds)));
    }

    $insertSubject = $pdo->prepare(
        'INSERT INTO subject '
        . '(subject_code, subject_name, semester, school_year, units, year_level, term, section, room, schedule, department_id, faculty_id) '
        . 'VALUES '
        . '(:code, :name, :semester, :schoolYear, :units, :yearLevel, :term, :section, :room, :schedule, :departmentId, NULL)'
    );

    $insertLink = $pdo->prepare(
        'INSERT INTO curriculum_subject (curriculum_id, subject_id) VALUES (:curriculumId, :subjectId)'
    );

    foreach ($normalizedSubjects as $index => $subject) {
        $insertSubject->execute([
            'code' => $subject['code'],
            'name' => $subject['name'],
            'semester' => $subject['semester'],
            'schoolYear' => $schoolYear,
            'units' => $subject['units'],
            'yearLevel' => $subject['yearLevel'],
            'term' => $subject['term'],
            'section' => $subject['section'],
            'room' => $subject['room'],
            'schedule' => $subject['schedule'],
            'departmentId' => $departmentId,
        ]);

        $subjectId = (int) $pdo->lastInsertId();
        $insertLink->execute([
            'curriculumId' => $curriculumId,
            'subjectId' => $subjectId,
        ]);

        stdout(sprintf(
            '  [%d] Imported %s - %s (subject_id=%d)',
            $index + 1,
            $subject['code'],
            $subject['name'],
            $subjectId
        ));
    }

    $pdo->commit();
    stdout('');
    stdout('Import completed successfully.');
} catch (Throwable $exception) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    stderr(sprintf('Import failed: %s', $exception->getMessage()));
    exit(1);
}