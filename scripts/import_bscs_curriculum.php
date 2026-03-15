<?php
/**
 * BSCS Curriculum Subject Import
 * 
 * Extracts all subjects from the BSCS curriculum image,
 * inserts them into the database linked to BSCS Curriculum id=10,
 * department_id=6 (COMPUTER SCIENCE).
 */

require dirname(__DIR__) . '/vendor/autoload.php';

$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$CURRICULUM_ID = 10;   // BSCS Curriculum (dept=6 COMPUTER SCIENCE)
$DEPARTMENT_ID = 6;    // COMPUTER SCIENCE

// ── Full BSCS Curriculum Data (extracted from image) ──────────────────
$curriculum = [
    // ═══ FIRST YEAR ═══
    // 1st Semester
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'GE 4',     'name' => 'Mathematics in the Modern World',           'units' => 3],
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'GE 5',     'name' => 'Purposive Communication',                   'units' => 3],
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'GE 6',     'name' => 'Art Appreciation',                          'units' => 3],
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'FL 1',     'name' => 'Akademiko sa Wikang Filipino',               'units' => 3],
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'CSC 100',  'name' => 'Introduction to Computing',                 'units' => 3],
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'CSC 101',  'name' => 'Computer Programming 1',                    'units' => 3],
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'PE 1',     'name' => 'Physical Fitness and Health',               'units' => 2],
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'NSTP 1',   'name' => 'National Service Training Program I',       'units' => 3],

    // 2nd Semester
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'GE 1',     'name' => 'Understanding the Self',                    'units' => 3],
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'GE 2',     'name' => 'Readings in Philippine History',            'units' => 3],
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'GE 3',     'name' => 'The Contemporary World',                    'units' => 3],
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'IT 1',     'name' => 'Filipino Literature',                       'units' => 3],
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'CSC 103',  'name' => 'Discrete Structures I',                     'units' => 3],
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'CSC 104',  'name' => 'Computer Programming II',                   'units' => 3],
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'CSC 105',  'name' => 'Data Structures & Algorithms',              'units' => 3],
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'PE 2',     'name' => 'Recreational Games and Sports',             'units' => 2],
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'NSTP 2',   'name' => 'National Service Training Program 2',       'units' => 3],

    // Enhancement
    ['year' => 'First Year', 'sem' => 'Enhancement',  'code' => 'EN PRECAL','name' => 'Pre-Calculus',                              'units' => 3],
    ['year' => 'First Year', 'sem' => 'Enhancement',  'code' => 'EN COMP',  'name' => 'Computer Hardware Servicing',               'units' => 3],
    ['year' => 'First Year', 'sem' => 'Enhancement',  'code' => 'EN BACAL', 'name' => 'Basic Calculus',                            'units' => 3],

    // ═══ SECOND YEAR ═══
    // 1st Semester
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'GE 7',    'name' => 'Science, Technology and Society',           'units' => 3],
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'GE 8',    'name' => 'Ethics',                                    'units' => 3],
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'CSC 200', 'name' => 'Object-Oriented Design & Programming',      'units' => 3],
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'CSC 201', 'name' => 'Information Management',                    'units' => 3],
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'CSC 202', 'name' => 'Discrete Structures II',                    'units' => 3],
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'CSC 203', 'name' => 'Algorithms and Complexity',                 'units' => 3],
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'CSC 204', 'name' => 'Programming Languages',                     'units' => 3],
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'Eng 127', 'name' => 'Business Communication I',                  'units' => 3],
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'PE 3',    'name' => 'Rhythmic and Social Recreation',            'units' => 2],

    // 2nd Semester
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'GE 8',    'name' => 'Life and Works of Rizal',                   'units' => 3],
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'GE 10',   'name' => 'Environmental Science',                     'units' => 3],
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'CSC 205', 'name' => 'Architecture and Organization',             'units' => 3],
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'CSC 206', 'name' => 'Human Computer Interaction',                'units' => 3],
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'CSC 207', 'name' => 'Operating Systems',                         'units' => 3],
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'CSC 208', 'name' => 'Modelling & Simulation',                    'units' => 3],
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'CSC 209', 'name' => 'Graphics & Visual Computing',               'units' => 3],
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'Eng 128', 'name' => 'Business Communication II',                 'units' => 3],
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'PE 4',    'name' => 'Cultural Presentation & Sports Management', 'units' => 2],

    // ═══ THIRD YEAR ═══
    // 1st Semester
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'GE 11',    'name' => 'Gender and Society',                        'units' => 3],
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'CSC 300',  'name' => 'Systems Analysis & Design',                 'units' => 3],
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'CSC 301',  'name' => 'Seminars & Field Trips',                    'units' => 3],
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'CSC 302',  'name' => 'Network and Communication',                 'units' => 3],
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'CSC 303',  'name' => 'Software Engineering I',                    'units' => 3],
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'CSC 304',  'name' => 'Web Programming',                           'units' => 3],
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'SYS 106',  'name' => 'Principles of Systems Thinking',            'units' => 3],
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'BPO 1',    'name' => 'Fund of BPO 1',                             'units' => 3],
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'SERV 100', 'name' => 'Service Culture',                           'units' => 3],

    // 2nd Semester
    ['year' => 'Third Year', 'sem' => '2nd Semester', 'code' => 'GE 13',    'name' => 'Philippine Popular Culture',                'units' => 3],
    ['year' => 'Third Year', 'sem' => '2nd Semester', 'code' => 'CSC 305',  'name' => 'Application Development & Emerging Technologies', 'units' => 3],
    ['year' => 'Third Year', 'sem' => '2nd Semester', 'code' => 'CSC 306',  'name' => 'Software Engineering II',                   'units' => 3],
    ['year' => 'Third Year', 'sem' => '2nd Semester', 'code' => 'CSC 307',  'name' => 'Intellectual Property Basic',               'units' => 3],
    ['year' => 'Third Year', 'sem' => '2nd Semester', 'code' => 'CSC 308',  'name' => 'Information Assurance & Security',          'units' => 3],
    ['year' => 'Third Year', 'sem' => '2nd Semester', 'code' => 'CSC 309',  'name' => 'Automata Theory & Formal Language',         'units' => 3],
    ['year' => 'Third Year', 'sem' => '2nd Semester', 'code' => 'BPO 2',    'name' => 'Fund of BPO 2',                             'units' => 3],
    ['year' => 'Third Year', 'sem' => '2nd Semester', 'code' => 'Math 305', 'name' => 'Numerical Analysis',                        'units' => 5],

    // ═══ SUMMER ═══
    ['year' => 'Third Year', 'sem' => 'Summer',        'code' => 'CSC 400', 'name' => 'Internship 1 (300 hours)',                  'units' => 3],

    // ═══ FOURTH YEAR ═══
    // 1st Semester
    ['year' => 'Fourth Year', 'sem' => '1st Semester', 'code' => 'CSC 401', 'name' => 'Internship 2 (500 hours)',                  'units' => 3],
    ['year' => 'Fourth Year', 'sem' => '1st Semester', 'code' => 'CSC 402', 'name' => 'Thesis 1',                                  'units' => 3],

    // 2nd Semester
    ['year' => 'Fourth Year', 'sem' => '2nd Semester', 'code' => 'CSC 403', 'name' => 'Thesis 2',                                  'units' => 3],
    ['year' => 'Fourth Year', 'sem' => '2nd Semester', 'code' => 'CSC 404', 'name' => 'Social Issues and Professional Practice',   'units' => 3],
    ['year' => 'Fourth Year', 'sem' => '2nd Semester', 'code' => 'CSC 405', 'name' => 'Artificial Intelligence',                   'units' => 3],
];

// ══════════════════════════════════════════════════════════════════════
// STEP 1: Clean duplicate BSCS curricula (keep only ID 10)
// ══════════════════════════════════════════════════════════════════════
echo "=== STEP 1: Cleanup ===\n";

// Remove duplicate empty BSCS curricula (IDs 8, 9)
foreach ([8, 9] as $dupId) {
    $pdo->exec("DELETE FROM curriculum_subject WHERE curriculum_id = $dupId");
    $pdo->exec("DELETE FROM curriculum WHERE id = $dupId");
    echo "  Removed duplicate BSCS Curriculum id=$dupId\n";
}

// Clean existing links for curriculum 10
$pdo->exec("DELETE FROM curriculum_subject WHERE curriculum_id = $CURRICULUM_ID");
echo "  Cleared old curriculum_subject links for id=$CURRICULUM_ID\n";

// ══════════════════════════════════════════════════════════════════════
// STEP 2: Insert all BSCS subjects
// ══════════════════════════════════════════════════════════════════════
echo "\n=== STEP 2: Import Subjects ===\n";

$insertSubject = $pdo->prepare("
    INSERT INTO subject (subject_code, subject_name, semester, year_level, units, school_year, department_id, faculty_id)
    VALUES (:code, :name, :sem, :year, :units, '2026', :dept, NULL)
");

$insertLink = $pdo->prepare("
    INSERT INTO curriculum_subject (curriculum_id, subject_id)
    VALUES (:cid, :sid)
");

$inserted = 0;
$totalUnits = 0;

foreach ($curriculum as $row) {
    $insertSubject->execute([
        'code'  => $row['code'],
        'name'  => $row['name'],
        'sem'   => $row['sem'],
        'year'  => $row['year'],
        'units' => $row['units'],
        'dept'  => $DEPARTMENT_ID,
    ]);

    $subjectId = (int) $pdo->lastInsertId();

    $insertLink->execute([
        'cid' => $CURRICULUM_ID,
        'sid' => $subjectId,
    ]);

    $inserted++;
    $totalUnits += $row['units'];
    echo "  [$inserted] {$row['code']} - {$row['name']} ({$row['units']} units) => ID: $subjectId\n";
}

echo "\n=== DONE ===\n";
echo "  Total subjects: $inserted\n";
echo "  Total units: $totalUnits\n";
echo "  Linked to BSCS Curriculum (ID: $CURRICULUM_ID, Dept: COMPUTER SCIENCE)\n";
