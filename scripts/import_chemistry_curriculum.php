<?php
/**
 * Import CHEMISTRY Curriculum 2026 subjects
 * Curriculum ID: 14, Department ID: 12
 */

$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$curriculumId = 14;
$departmentId = 12;
$schoolYear   = '2026';

$subjects = [
    // ===== FIRST YEAR — 1st Semester =====
    ['code' => 'GE 1',      'name' => 'Understanding the Self',                          'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'GE 2',      'name' => 'Readings in Philippine History',                   'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'GE 3',      'name' => 'The Contemporary World',                           'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'FIL 1',     'name' => 'Akademiko sa Wikang Filipino',                     'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'CHEM 100',  'name' => 'Principles of Chemistry',                          'units' => 5, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'CHEM 101',  'name' => 'Chemistry Tutorial 1',                             'units' => 1, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'PE 1',      'name' => 'Physical Fitness and Health',                       'units' => 2, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'NSTP 1',    'name' => 'National Service Training Program 1',               'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],

    // ===== FIRST YEAR — 2nd Semester =====
    ['code' => 'GE 5',      'name' => 'Purposive Communication',                          'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'GE 6',      'name' => 'Art Appreciation',                                 'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'GE 7',      'name' => 'Science, Technology and Society',                   'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'MATH 100',  'name' => 'Calculus I',                                       'units' => 5, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'PHYS 1',    'name' => 'Physics 1',                                        'units' => 5, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 102',  'name' => 'Inorganic Chemistry 1',                            'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 103',  'name' => 'Chemistry Tutorial 2',                             'units' => 1, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'PE 2',      'name' => 'Recreational Games and Sports',                    'units' => 2, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'NSTP 2',    'name' => 'National Service Training Program 2',               'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],

    // ===== FIRST YEAR — Enhancement =====
    ['code' => 'EN PRECAL', 'name' => 'Pre-Calculus',                                     'units' => 3, 'year' => 'First Year', 'sem' => 'Enhancement'],
    ['code' => 'EN BACAL',  'name' => 'Basic Calculus',                                   'units' => 3, 'year' => 'First Year', 'sem' => 'Enhancement'],

    // ===== SECOND YEAR — 1st Semester =====
    ['code' => 'MATH 102',  'name' => 'Calculus II',                                      'units' => 5, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'PHYS 2',    'name' => 'Physics 2',                                        'units' => 5, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'CHEM 200',  'name' => 'Analytical Chemistry 1',                           'units' => 5, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'CHEM 201',  'name' => 'Organic Chemistry 1',                              'units' => 5, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'CHEM 202',  'name' => 'Chemistry Tutorial 3',                             'units' => 1, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'GEN BIO',   'name' => 'General Biology',                                  'units' => 3, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'PE 3',      'name' => 'Rhythmic and Social Recreation',                   'units' => 2, 'year' => 'Second Year', 'sem' => '1st Semester'],

    // ===== SECOND YEAR — 2nd Semester =====
    ['code' => 'GE 10A',    'name' => 'Living in the IT Era',                             'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'MATH 204',  'name' => 'Statistics in Chemistry',                          'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 203',  'name' => 'Organic Chemistry 2',                              'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 204',  'name' => 'Physical Chemistry 1',                             'units' => 5, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 205',  'name' => 'Analytical Chemistry 2',                           'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 206',  'name' => 'Chemistry Tutorial 4',                             'units' => 1, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'PE 4',      'name' => 'Cultural Presentation & Sports Management',        'units' => 2, 'year' => 'Second Year', 'sem' => '2nd Semester'],

    // ===== THIRD YEAR — 1st Semester =====
    ['code' => 'GE 4',      'name' => 'Mathematics in the Modern World',                  'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'GE 9',      'name' => 'Life and Works of Rizal',                          'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'LIT 1',     'name' => 'Philippine Literature',                            'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'CHEM 300',  'name' => 'Biochemistry 1',                                   'units' => 5, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'CHEM 301',  'name' => 'Physical Chemistry 2',                             'units' => 5, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'CHEM 302',  'name' => 'Analytical Chemistry 3',                           'units' => 5, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'CHEM 303',  'name' => 'Chemistry Tutorial 5',                             'units' => 1, 'year' => 'Third Year', 'sem' => '1st Semester'],

    // ===== THIRD YEAR — 2nd Semester =====
    ['code' => 'GE 8',      'name' => 'Ethics',                                           'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 208',   'name' => 'Microbiology',                                     'units' => 5, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 304',  'name' => 'Chemical Innovation',                              'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 305',  'name' => 'Physical Chemistry 3',                             'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 306',  'name' => 'Biochemistry 2',                                   'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 307',  'name' => 'Introduction to Chemical Research Methods',        'units' => 1, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 308',  'name' => 'Chemistry Tutorial 6',                             'units' => 1, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'Entrep',    'name' => 'Entrepreneurship',                                 'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],

    // ===== THIRD YEAR — Summer =====
    ['code' => 'CHEM 309',  'name' => 'Internship 1 (200 hours)',                         'units' => 3, 'year' => 'Third Year', 'sem' => 'Summer'],

    // ===== FOURTH YEAR — 1st Semester =====
    ['code' => 'GE 11',     'name' => 'Gender and Society',                               'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'CHEM 401',  'name' => 'Environmental Chemistry',                          'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'CHEM 402',  'name' => 'Electrochemistry',                                 'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'CHEM 403',  'name' => 'Inorganic Chemistry 2',                            'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'CHEM 404',  'name' => 'Thesis',                                           'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],

    // ===== FOURTH YEAR — 2nd Semester =====
    ['code' => 'GE 12',     'name' => 'Philippine Popular Culture',                       'units' => 3, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 405',  'name' => 'Industrial Chemistry',                             'units' => 3, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 406',  'name' => 'Advanced Organic Chemistry',                       'units' => 3, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 407',  'name' => 'Research Writing and Seminar',                     'units' => 2, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 408',  'name' => 'Biochemistry 3',                                   'units' => 3, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
];

echo "Importing " . count($subjects) . " subjects for CHEMISTRY Curriculum 2026...\n\n";

$insertSubject = $pdo->prepare("
    INSERT INTO subject (department_id, subject_code, subject_name, units, year_level, semester, school_year, faculty_id)
    VALUES (:dept, :code, :name, :units, :year, :sem, :sy, NULL)
");

$linkCurriculum = $pdo->prepare("
    INSERT INTO curriculum_subject (curriculum_id, subject_id)
    VALUES (:cid, :sid)
");

$totalUnits = 0;
$count = 0;

foreach ($subjects as $s) {
    $insertSubject->execute([
        ':dept'  => $departmentId,
        ':code'  => $s['code'],
        ':name'  => $s['name'],
        ':units' => $s['units'],
        ':year'  => $s['year'],
        ':sem'   => $s['sem'],
        ':sy'    => $schoolYear,
    ]);
    $subjectId = $pdo->lastInsertId();

    $linkCurriculum->execute([
        ':cid' => $curriculumId,
        ':sid' => $subjectId,
    ]);

    $totalUnits += $s['units'];
    $count++;

    echo "  [{$count}] {$s['code']} — {$s['name']} ({$s['units']} units) → subject ID {$subjectId}\n";
}

echo "\n✅ Done! Inserted {$count} subjects, {$totalUnits} total units.\n";
echo "   Linked to CHEMISTRY Curriculum (ID: {$curriculumId}), Department CHEMISTRY (ID: {$departmentId})\n";
