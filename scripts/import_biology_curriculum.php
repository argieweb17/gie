<?php
/**
 * Import BIOLOGY Curriculum 2026 subjects
 * Curriculum ID: 15, Department ID: 10
 */

$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$curriculumId = 15;
$departmentId = 10;
$schoolYear   = '2026';

$subjects = [
    // ===== FIRST YEAR — 1st Semester =====
    ['code' => 'GE 1',      'name' => 'Understanding the Self',                          'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'GE 2',      'name' => 'Readings in Philippine History',                   'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'FIL 1',     'name' => 'Akademiko sa Wikang Filipino',                     'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'BIO 1',     'name' => 'General Botany',                                   'units' => 5, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'BIO 2',     'name' => 'General Zoology',                                  'units' => 5, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'PE 1',      'name' => 'Physical Fitness and Health',                       'units' => 2, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'NSTP 1',    'name' => 'National Service Training Program 1',               'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],

    // ===== FIRST YEAR — 2nd Semester =====
    ['code' => 'GE 5',      'name' => 'Purposive Communication',                          'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'GE 6',      'name' => 'Art Appreciation',                                 'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 100',   'name' => 'Systematics',                                      'units' => 5, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 101',   'name' => 'Statistical Biology',                              'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 102',   'name' => 'Organic Chemistry',                                'units' => 5, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'PE 2',      'name' => 'Recreational Games and Sports',                    'units' => 2, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'NSTP 2',    'name' => 'National Service Training Program 2',               'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],

    // ===== FIRST YEAR — Enhancement =====
    ['code' => 'EN BIO',    'name' => 'Biology',                                          'units' => 3, 'year' => 'First Year', 'sem' => 'Enhancement'],
    ['code' => 'EN CHEM',   'name' => 'Chemistry',                                        'units' => 3, 'year' => 'First Year', 'sem' => 'Enhancement'],

    // ===== SECOND YEAR — 1st Semester =====
    ['code' => 'GE 7',      'name' => 'Science, Technology and Society',                   'units' => 3, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'GE 9',      'name' => 'Life and Works of Rizal',                          'units' => 3, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'BIO 200',   'name' => 'Microbiology',                                     'units' => 5, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'BIO 201',   'name' => 'General Ecology',                                  'units' => 5, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'BIO 202',   'name' => 'Analytical Chemistry',                             'units' => 3, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'BIO 203',   'name' => 'Biotutorial 1',                                    'units' => 1, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'PE 3',      'name' => 'Rhythmic and Social Recreation',                   'units' => 2, 'year' => 'Second Year', 'sem' => '1st Semester'],

    // ===== SECOND YEAR — 2nd Semester =====
    ['code' => 'GE 8',      'name' => 'Ethics',                                           'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'GE 10',     'name' => 'Environmental Science',                            'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 204',   'name' => 'Evolutionary Bio',                                 'units' => 5, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 205',   'name' => 'Genetics',                                         'units' => 5, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 206',   'name' => 'Biochemistry',                                     'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 207',   'name' => 'Biotutorial 2',                                    'units' => 1, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'PE 4',      'name' => 'Cultural Presentation & Sports Management',        'units' => 2, 'year' => 'Second Year', 'sem' => '2nd Semester'],

    // ===== THIRD YEAR — 1st Semester =====
    ['code' => 'GE 3',      'name' => 'The Contemporary World',                           'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'GE 4',      'name' => 'Mathematics in the Modern World',                  'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'LIT 1',     'name' => 'Philippine Literature',                            'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'BIO 300',   'name' => 'Cell & Molecular Bio',                             'units' => 5, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'BIO 301',   'name' => 'General Physio',                                   'units' => 5, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'BIO 302',   'name' => 'Biotutorial 4',                                    'units' => 1, 'year' => 'Third Year', 'sem' => '1st Semester'],

    // ===== THIRD YEAR — 2nd Semester =====
    ['code' => 'GE 11',     'name' => 'Gender and Society',                               'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'GE 12',     'name' => 'Philippine Popular Culture',                       'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 303',   'name' => 'Biophysics',                                       'units' => 5, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 304',   'name' => 'Developmental Bio',                                'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 305',   'name' => 'Invertebrate Zoology',                             'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 306',   'name' => 'Thesis 1 (Research Proposal Writing)',              'units' => 2, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 307',   'name' => 'Biotutorial 5',                                    'units' => 5, 'year' => 'Third Year', 'sem' => '2nd Semester'],

    // ===== THIRD YEAR — Summer =====
    ['code' => 'BIO 400',   'name' => 'On-the-Job Training (160 hours)',                   'units' => 3, 'year' => 'Third Year', 'sem' => 'Summer'],

    // ===== FOURTH YEAR — 1st Semester =====
    ['code' => 'ED 101',    'name' => 'Child and Adolescent Learners and Learning Principles', 'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'BIO 401',   'name' => 'Comparative Vertebrate Anatomy',                   'units' => 5, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'BIO 402',   'name' => 'Animal Physiology',                                'units' => 5, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'BIO 403',   'name' => 'Immunology',                                       'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'BIO 404',   'name' => 'Thesis 2 (Data Gathering & Interpretation)',        'units' => 2, 'year' => 'Fourth Year', 'sem' => '1st Semester'],

    // ===== FOURTH YEAR — 2nd Semester =====
    ['code' => 'ED 102',    'name' => 'Building and Enhancing New Literacies Across the Curriculum', 'units' => 3, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 406',   'name' => 'Parasitology',                                     'units' => 5, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 407',   'name' => 'Animal Histology',                                 'units' => 5, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 408',   'name' => 'Ichthyology',                                      'units' => 3, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'BIO 409',   'name' => 'Thesis 3 (Manuscript Writing and BioSeminar)',      'units' => 2, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
];

echo "Importing " . count($subjects) . " subjects for BIOLOGY Curriculum 2026...\n\n";

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
echo "   Linked to BIOLOGY Curriculum (ID: {$curriculumId}), Department BIOLOGY (ID: {$departmentId})\n";
