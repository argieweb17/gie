<?php
/**
 * Import GEOLOGY Curriculum 2026 subjects
 * Curriculum ID: 13, Department ID: 9
 */

$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$curriculumId = 13;
$departmentId = 9;
$schoolYear   = '2026';

$subjects = [
    // ===== FIRST YEAR — 1st Semester =====
    ['code' => 'GE 1',      'name' => 'Understanding the Self',                     'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'GE 2',      'name' => 'Readings in Philippine History',              'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'MATH 1',    'name' => 'College Algebra',                             'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'CHEM 1',    'name' => 'General Chemistry 1',                         'units' => 5, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'ID 1',      'name' => 'Engineering Drawing',                         'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'GEOL 100',  'name' => 'Principles of Geology with lab',              'units' => 4, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'PE 1',      'name' => 'Physical Fitness and Health',                 'units' => 2, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'NSTP 1',    'name' => 'National Service Training Program 1',         'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],

    // ===== FIRST YEAR — 2nd Semester =====
    ['code' => 'GE 3',      'name' => 'The Contemporary World',                     'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'GE 4',      'name' => 'Mathematics in the Modern World',            'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'GE 5',      'name' => 'Purposive Communication',                    'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'FIL 1',     'name' => 'Akademiko sa Wikang Filipino',                'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'MATH 2',    'name' => 'Plane Trigonometry',                          'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'CHEM 3',    'name' => 'Stoichiometry',                               'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'GEOL 101',  'name' => 'Mineralogy',                                 'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'PE 2',      'name' => 'Recreational Games and Sports',               'units' => 2, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'NSTP 2',    'name' => 'National Service Training Program 2',         'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],

    // ===== FIRST YEAR — Enhancement =====
    ['code' => 'EN DRRR',   'name' => 'Disaster Readiness and Risk Reduction',      'units' => 3, 'year' => 'First Year', 'sem' => 'Enhancement'],

    // ===== SECOND YEAR — 1st Semester =====
    ['code' => 'GE 7',      'name' => 'Science, Technology and Society',             'units' => 3, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'PHYS 1',    'name' => 'General Physics 1',                          'units' => 5, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'CHEM 200',  'name' => 'Analytical Chemistry 1',                     'units' => 5, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'BIO 1',     'name' => 'General Biology (3 lec/2 lab)',               'units' => 5, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'GEOL 200',  'name' => 'Plane Surveying',                            'units' => 3, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'GEOL 201',  'name' => 'Petrology',                                  'units' => 3, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'PE 3',      'name' => 'Rhythmic and Social Recreation',              'units' => 2, 'year' => 'Second Year', 'sem' => '1st Semester'],

    // ===== SECOND YEAR — 2nd Semester =====
    ['code' => 'GE 8',      'name' => 'Ethics',                                     'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'PHYS 2',    'name' => 'General Physics 2',                          'units' => 5, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'PHYS 3',    'name' => 'Optics',                                     'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'MATH 100',  'name' => 'Calculus I',                                 'units' => 5, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'GEOL 202',  'name' => 'Historical Geology & Principles of Strat',   'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'GEOL 203',  'name' => 'Structural Geology',                         'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'PE 4',      'name' => 'Cultural Presentation & Sports Management',  'units' => 2, 'year' => 'Second Year', 'sem' => '2nd Semester'],

    // ===== SECOND YEAR — Summer =====
    ['code' => 'GEOL 204',  'name' => 'Field Geology',                              'units' => 3, 'year' => 'Second Year', 'sem' => 'Summer'],

    // ===== THIRD YEAR — 1st Semester =====
    ['code' => 'GE 6',      'name' => 'Art Appreciation',                           'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'GE 10',     'name' => 'Environmental Science',                      'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'MATH 102',  'name' => 'Calculus II',                                'units' => 5, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'GEOL 300',  'name' => 'Optical Mineralogy',                         'units' => 4, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'GEOL 301',  'name' => 'Structural Geology and Tectonics',           'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'GEOL 302',  'name' => 'Elementary Paleontology',                    'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'COMP 3',    'name' => 'Advance Software Applications',              'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],

    // ===== THIRD YEAR — 2nd Semester =====
    ['code' => 'GE 9',      'name' => 'Life and Works of Rizal',                    'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'GE 11',     'name' => 'Gender and Society',                         'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'GE 12',     'name' => 'Philippine Popular Culture',                 'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'LIT 1',     'name' => 'Philippine Literature',                      'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'GEOL 303',  'name' => 'Petrography',                                'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'GEOL 304',  'name' => 'Applied Eng\'g Geology',                     'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'GEOL 305',  'name' => 'Sedimentology',                              'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'GEOL 306',  'name' => 'Geol in Phils and Southeast Asia',           'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],

    // ===== THIRD YEAR — Summer =====
    ['code' => 'GEOL 307',  'name' => 'Practicum',                                  'units' => 6, 'year' => 'Third Year', 'sem' => 'Summer'],

    // ===== FOURTH YEAR — 1st Semester =====
    ['code' => 'GEOL 400',  'name' => 'Economic Geology',                           'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'GEOL 401',  'name' => 'Elements of Geophysics',                     'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'GEOL 402',  'name' => 'GeoChem',                                    'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'GEOL 403',  'name' => 'Principles of Volcanology and Seismology',   'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'GEOL 404',  'name' => 'Computer Methods in Geology',                'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'MATH 204',  'name' => 'Probability and Statistics',                 'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],

    // ===== FOURTH YEAR — 2nd Semester =====
    ['code' => 'GEOL 405',  'name' => 'Seminar in Geology',                         'units' => 2, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'GEOL 406',  'name' => 'GeoStat',                                    'units' => 3, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'GEOL 407',  'name' => 'Environmental & Mining Laws',                'units' => 3, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'GEOL 408',  'name' => 'NonMetalliferous & Metalliferous Ores',      'units' => 3, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'GEOL 409',  'name' => 'Environmental Geology',                      'units' => 3, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'GEOL 410',  'name' => 'Assessment',                                 'units' => 3, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
];

echo "Importing " . count($subjects) . " subjects for GEOLOGY Curriculum 2026...\n\n";

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
echo "   Linked to GEOLOGY Curriculum (ID: {$curriculumId}), Department GEOLOGY (ID: {$departmentId})\n";
