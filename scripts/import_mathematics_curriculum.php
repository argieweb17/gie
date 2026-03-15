<?php
/**
 * Import MATHEMATICS Curriculum 2026 subjects
 * Curriculum ID: 12, Department ID: 8
 */

$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$curriculumId = 12;
$departmentId = 8;
$schoolYear   = '2026';

$subjects = [
    // ===== FIRST YEAR — 1st Semester =====
    ['code' => 'GE 1',      'name' => 'Understanding the Self',                          'units' => 3, 'year' => 1, 'sem' => '1st Semester'],
    ['code' => 'GE 2',      'name' => 'Readings in Philippine History',                   'units' => 3, 'year' => 1, 'sem' => '1st Semester'],
    ['code' => 'GE 3',      'name' => 'The Contemporary World',                           'units' => 3, 'year' => 1, 'sem' => '1st Semester'],
    ['code' => 'MATH 100',  'name' => 'Calculus I',                                       'units' => 5, 'year' => 1, 'sem' => '1st Semester'],
    ['code' => 'MATH 101',  'name' => 'Fundamentals of Computing 1',                      'units' => 3, 'year' => 1, 'sem' => '1st Semester'],
    ['code' => 'PE 1',      'name' => 'Physical Fitness and Health',                       'units' => 2, 'year' => 1, 'sem' => '1st Semester'],
    ['code' => 'NSTP 1',    'name' => 'National Service Training Program 1',               'units' => 3, 'year' => 1, 'sem' => '1st Semester'],

    // ===== FIRST YEAR — 2nd Semester =====
    ['code' => 'GE 4',      'name' => 'Mathematics in the Modern World',                  'units' => 3, 'year' => 1, 'sem' => '2nd Semester'],
    ['code' => 'GE 5',      'name' => 'Purposive Communication',                          'units' => 3, 'year' => 1, 'sem' => '2nd Semester'],
    ['code' => 'GE 6',      'name' => 'Art Appreciation',                                 'units' => 3, 'year' => 1, 'sem' => '2nd Semester'],
    ['code' => 'MATH 102',  'name' => 'Calculus II',                                      'units' => 5, 'year' => 1, 'sem' => '2nd Semester'],
    ['code' => 'MATH 103',  'name' => 'Fundamental Concepts of Mathematics',              'units' => 3, 'year' => 1, 'sem' => '2nd Semester'],
    ['code' => 'PE 2',      'name' => 'Recreational Games and Sports',                    'units' => 2, 'year' => 1, 'sem' => '2nd Semester'],
    ['code' => 'NSTP 2',    'name' => 'National Service Training Program 2',              'units' => 3, 'year' => 1, 'sem' => '2nd Semester'],

    // ===== FIRST YEAR — Enhancement =====
    ['code' => 'EN PRECAL', 'name' => 'Pre-Calculus',                                     'units' => 3, 'year' => 1, 'sem' => 'Enhancement'],
    ['code' => 'EN BACAL',  'name' => 'Basic Calculus',                                   'units' => 3, 'year' => 1, 'sem' => 'Enhancement'],

    // ===== SECOND YEAR — 1st Semester =====
    ['code' => 'GE 7',      'name' => 'Science, Technology and Society',                  'units' => 3, 'year' => 2, 'sem' => '1st Semester'],
    ['code' => 'FIL 1',     'name' => 'Akademiko sa Wikang Filipino',                     'units' => 3, 'year' => 2, 'sem' => '1st Semester'],
    ['code' => 'MATH 200',  'name' => 'Calculus III',                                     'units' => 5, 'year' => 2, 'sem' => '1st Semester'],
    ['code' => 'MATH 201',  'name' => 'Abstract Algebra',                                 'units' => 3, 'year' => 2, 'sem' => '1st Semester'],
    ['code' => 'PHYS 1',    'name' => 'General Physics I',                                'units' => 5, 'year' => 2, 'sem' => '1st Semester'],
    ['code' => 'PE 3',      'name' => 'Rhythmic and Social Recreation',                   'units' => 2, 'year' => 2, 'sem' => '1st Semester'],
    ['code' => 'ED 101',    'name' => 'Child & Adolescent Learners & Learning Principles','units' => 3, 'year' => 2, 'sem' => '1st Semester'],

    // ===== SECOND YEAR — 2nd Semester =====
    ['code' => 'GE 8',      'name' => 'Ethics',                                           'units' => 3, 'year' => 2, 'sem' => '2nd Semester'],
    ['code' => 'GE 10',     'name' => 'Environmental Science',                            'units' => 3, 'year' => 2, 'sem' => '2nd Semester'],
    ['code' => 'MATH 202',  'name' => 'Advanced Calculus',                                'units' => 3, 'year' => 2, 'sem' => '2nd Semester'],
    ['code' => 'MATH 203',  'name' => 'Linear Algebra',                                   'units' => 3, 'year' => 2, 'sem' => '2nd Semester'],
    ['code' => 'MATH 204',  'name' => 'Probability',                                      'units' => 3, 'year' => 2, 'sem' => '2nd Semester'],
    ['code' => 'CHEM 1',    'name' => 'General Chemistry I',                              'units' => 5, 'year' => 2, 'sem' => '2nd Semester'],
    ['code' => 'PE 4',      'name' => 'Cultural Presentation & Sports Management',        'units' => 2, 'year' => 2, 'sem' => '2nd Semester'],
    ['code' => 'ED 102',    'name' => 'Building and Enhancing New Literacies Across the Curriculum', 'units' => 3, 'year' => 2, 'sem' => '2nd Semester'],

    // ===== THIRD YEAR — 1st Semester =====
    ['code' => 'GE 9',      'name' => 'Life and Works of Rizal',                          'units' => 3, 'year' => 3, 'sem' => '1st Semester'],
    ['code' => 'GE 11',     'name' => 'Gender and Society',                               'units' => 3, 'year' => 3, 'sem' => '1st Semester'],
    ['code' => 'MATH 300',  'name' => 'Real Analysis',                                    'units' => 3, 'year' => 3, 'sem' => '1st Semester'],
    ['code' => 'MATH 301',  'name' => 'Differential Equations',                           'units' => 3, 'year' => 3, 'sem' => '1st Semester'],
    ['code' => 'MATH 302',  'name' => 'Set Theory',                                       'units' => 3, 'year' => 3, 'sem' => '1st Semester'],
    ['code' => 'MATH 303',  'name' => 'Statistical Theory',                               'units' => 3, 'year' => 3, 'sem' => '1st Semester'],
    ['code' => 'ED 201',    'name' => 'Technology for Teaching & Learning 1',              'units' => 3, 'year' => 3, 'sem' => '1st Semester'],
    ['code' => 'ED 203',    'name' => 'Facilitating Learner-Centered Teaching',            'units' => 3, 'year' => 3, 'sem' => '1st Semester'],

    // ===== THIRD YEAR — 2nd Semester =====
    ['code' => 'GE 12',     'name' => 'Philippine Popular Culture',                       'units' => 3, 'year' => 3, 'sem' => '2nd Semester'],
    ['code' => 'LIT 1',     'name' => 'Philippine Literature',                            'units' => 3, 'year' => 3, 'sem' => '2nd Semester'],
    ['code' => 'MATH 304',  'name' => 'Modern Geometry',                                  'units' => 3, 'year' => 3, 'sem' => '2nd Semester'],
    ['code' => 'MATH 305',  'name' => 'Numerical Analysis',                               'units' => 3, 'year' => 3, 'sem' => '2nd Semester'],
    ['code' => 'MATH 306',  'name' => 'Discrete Mathematics',                             'units' => 3, 'year' => 3, 'sem' => '2nd Semester'],
    ['code' => 'MATH 307',  'name' => 'History and Development of Fundamental Ideas',     'units' => 3, 'year' => 3, 'sem' => '2nd Semester'],
    ['code' => 'ED 202',    'name' => 'The Teaching Profession',                           'units' => 3, 'year' => 3, 'sem' => '2nd Semester'],

    // ===== FOURTH YEAR — 1st Semester =====
    ['code' => 'MATH 400',  'name' => 'Complex Analysis',                                 'units' => 3, 'year' => 4, 'sem' => '1st Semester'],
    ['code' => 'MATH 401',  'name' => 'Elementary Number Theory',                         'units' => 3, 'year' => 4, 'sem' => '1st Semester'],
    ['code' => 'MATH 402',  'name' => 'Operations Research I',                            'units' => 3, 'year' => 4, 'sem' => '1st Semester'],
    ['code' => 'MATH 403',  'name' => 'Partial Differential Equations',                   'units' => 3, 'year' => 4, 'sem' => '1st Semester'],
    ['code' => 'MATH 404',  'name' => 'Thesis I',                                         'units' => 3, 'year' => 4, 'sem' => '1st Semester'],

    // ===== FOURTH YEAR — 2nd Semester =====
    ['code' => 'MATH 403',  'name' => 'Graph Theory & Applications',                      'units' => 3, 'year' => 4, 'sem' => '2nd Semester'],
    ['code' => 'MATH 404',  'name' => 'Thesis II or Special Problem',                     'units' => 3, 'year' => 4, 'sem' => '2nd Semester'],
    ['code' => 'MATH 405',  'name' => 'Mathematical Finance',                             'units' => 3, 'year' => 4, 'sem' => '2nd Semester'],
    ['code' => 'ED 304',    'name' => 'The Teacher & the Community, School Culture & Organizational Leadership', 'units' => 3, 'year' => 4, 'sem' => '2nd Semester'],
];

echo "Importing " . count($subjects) . " subjects for MATHEMATICS Curriculum 2026...\n\n";

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
echo "   Linked to MATHEMATICS Curriculum (ID: {$curriculumId}), Department MATHEMATICS (ID: {$departmentId})\n";
