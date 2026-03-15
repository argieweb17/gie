<?php
/**
 * Psychology Curriculum Subject Import
 * 
 * Extracts all subjects from the Psychology curriculum image,
 * inserts them into the database linked to PYSCHOLOGY Curriculum id=11.
 */

$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$CURRICULUM_ID = 11;   // PYSCHOLOGY curriculum 2026
$DEPARTMENT_ID = 11;   // PYSCHOLOGY

// ── Full Psychology Curriculum Data ───────────────────────────────────
$curriculum = [
    // ═══════════════════════════════════════════
    //  FIRST YEAR — 1st Semester
    // ═══════════════════════════════════════════
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'GE 1',      'name' => 'Understanding the Self',                       'units' => 3],
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'GE 2',      'name' => 'Readings in Philippine History',                'units' => 3],
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'GE 3',      'name' => 'The Contemporary World',                        'units' => 3],
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'GE 4',      'name' => 'Mathematics in the Modern World',               'units' => 3],
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'GE 5',      'name' => 'Purposive Communication',                       'units' => 3],
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'PSYCH 100', 'name' => 'Introduction to Psychology',                    'units' => 3],
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'CHEM 104',  'name' => 'Organic Chemistry with Biochemistry',           'units' => 5],
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'PE 1',      'name' => 'Physical Fitness and Health',                   'units' => 2],
    ['year' => 'First Year', 'sem' => '1st Semester', 'code' => 'NSTP 1',    'name' => 'National Service Training Program 1',           'units' => 3],

    // ═══════════════════════════════════════════
    //  FIRST YEAR — 2nd Semester
    // ═══════════════════════════════════════════
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'GE 6',      'name' => 'Art Appreciation',                              'units' => 3],
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'GE 7',      'name' => 'Science, Technology and Society',               'units' => 3],
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'GE 8',      'name' => 'Ethics',                                        'units' => 3],
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'PSYCH 101', 'name' => 'Psychological Statistics',                      'units' => 5],
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'PSYCH 102', 'name' => 'Psychology of Gender and Sexuality',            'units' => 3],
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'FIL 1',     'name' => 'Akademiko sa Wikang Filipino',                  'units' => 3],
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'PE 2',      'name' => 'Recreational Games and Sports',                 'units' => 2],
    ['year' => 'First Year', 'sem' => '2nd Semester', 'code' => 'NSTP 2',    'name' => 'National Service Training Program 2',           'units' => 3],

    // Enhancement (For OFF-TRACK Senior High School graduates)
    ['year' => 'First Year', 'sem' => 'Enhancement',  'code' => 'EN CHEM',   'name' => 'Chemistry',                                     'units' => 3],
    ['year' => 'First Year', 'sem' => 'Enhancement',  'code' => 'EN BIO',    'name' => 'Biology',                                       'units' => 3],

    // ═══════════════════════════════════════════
    //  SECOND YEAR — 1st Semester
    // ═══════════════════════════════════════════
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'GE 9',      'name' => 'Life and Works of Rizal',                     'units' => 3],
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'GE 10',     'name' => 'Environmental Science',                       'units' => 3],
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'PSYCH 200', 'name' => 'Theories of Personality',                     'units' => 3],
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'PSYCH 201', 'name' => 'Developmental Psychology',                    'units' => 3],
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'PSYCH 202', 'name' => 'Educational Psychology',                      'units' => 3],
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'BIO 2',     'name' => 'General Zoology',                             'units' => 5],
    ['year' => 'Second Year', 'sem' => '1st Semester', 'code' => 'PE 3',      'name' => 'Rhythmic and Social Recreation',              'units' => 2],

    // ═══════════════════════════════════════════
    //  SECOND YEAR — 2nd Semester
    // ═══════════════════════════════════════════
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'PSYCH 203',  'name' => 'Physiological Psychology',                   'units' => 3],
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'PSYCH 204',  'name' => 'Cognitive Psychology',                       'units' => 3],
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'PSYCH 205',  'name' => 'Experimental Psychology',                    'units' => 5],
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'PSYCH 206',  'name' => 'Filipino Psychology',                        'units' => 3],
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'ICT 100',    'name' => 'Computer Fundamentals',                      'units' => 3],
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'SCIENCE 100','name' => 'Human Anatomy and Physiology',               'units' => 5],
    ['year' => 'Second Year', 'sem' => '2nd Semester', 'code' => 'PE 4',       'name' => 'Cultural Presentation & Sports Management',  'units' => 2],

    // ═══════════════════════════════════════════
    //  THIRD YEAR — 1st Semester
    // ═══════════════════════════════════════════
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'GE 11',      'name' => 'Gender and Society',                          'units' => 3],
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'LIT 1',      'name' => 'Philippine Literature',                       'units' => 3],
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'PSYCH 300',  'name' => 'Psychological Assessment',                    'units' => 5],
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'PSYCH 301',  'name' => 'Social Psychology',                           'units' => 3],
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'PSYCH 302',  'name' => 'Positive Psychology',                         'units' => 3],
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'PSYCH 303',  'name' => 'Intro to Counseling',                         'units' => 3],
    ['year' => 'Third Year', 'sem' => '1st Semester', 'code' => 'BIO 204',    'name' => 'Genetics',                                    'units' => 3],

    // ═══════════════════════════════════════════
    //  THIRD YEAR — 2nd Semester
    // ═══════════════════════════════════════════
    ['year' => 'Third Year', 'sem' => '2nd Semester', 'code' => 'GE 12',      'name' => 'Philippine Popular Culture',                  'units' => 3],
    ['year' => 'Third Year', 'sem' => '2nd Semester', 'code' => 'PSYCH 304',  'name' => 'Field Methods in Psychology',                 'units' => 5],
    ['year' => 'Third Year', 'sem' => '2nd Semester', 'code' => 'PSYCH 305',  'name' => 'Industrial-Organizational Psychology',        'units' => 3],
    ['year' => 'Third Year', 'sem' => '2nd Semester', 'code' => 'PSYCH 306',  'name' => 'Abnormal Psychology',                         'units' => 3],
    ['year' => 'Third Year', 'sem' => '2nd Semester', 'code' => 'PSYCH 307',  'name' => 'Group Dynamics',                              'units' => 3],
    ['year' => 'Third Year', 'sem' => '2nd Semester', 'code' => 'PSYCH 308',  'name' => 'Disaster and Mental Health',                  'units' => 5],
    ['year' => 'Third Year', 'sem' => '2nd Semester', 'code' => 'PHYS 3',     'name' => 'Modern Physics',                              'units' => 3],

    // ═══════════════════════════════════════════
    //  FOURTH YEAR — 1st Semester
    // ═══════════════════════════════════════════
    ['year' => 'Fourth Year', 'sem' => '1st Semester', 'code' => 'PSYCH 400', 'name' => 'Research in Psychology Thesis 1',             'units' => 3],
    ['year' => 'Fourth Year', 'sem' => '1st Semester', 'code' => 'PSYCH 401', 'name' => 'Practicum 1',                                'units' => 3],
    ['year' => 'Fourth Year', 'sem' => '1st Semester', 'code' => 'PSYCH 402', 'name' => 'Strategic Human Resource Management',        'units' => 3],
    ['year' => 'Fourth Year', 'sem' => '1st Semester', 'code' => 'PSYCH 403', 'name' => 'Competency Assessment 1',                    'units' => 3],

    // ═══════════════════════════════════════════
    //  FOURTH YEAR — 2nd Semester
    // ═══════════════════════════════════════════
    ['year' => 'Fourth Year', 'sem' => '2nd Semester', 'code' => 'PSYCH 404', 'name' => 'Research in Psychology Thesis 2',             'units' => 3],
    ['year' => 'Fourth Year', 'sem' => '2nd Semester', 'code' => 'PSYCH 405', 'name' => 'Practicum 2',                                'units' => 3],
    ['year' => 'Fourth Year', 'sem' => '2nd Semester', 'code' => 'PSYCH 406', 'name' => 'Competency Assessment 2',                    'units' => 3],
];

// ══════════════════════════════════════════════════════════════════════
// STEP 1: Clear old curriculum links & insert all subjects
// ══════════════════════════════════════════════════════════════════════
echo "=== Psychology Curriculum Import ===\n\n";

// Delete old curriculum_subject links for this curriculum
$pdo->exec("DELETE FROM curriculum_subject WHERE curriculum_id = $CURRICULUM_ID");
echo "  Cleared old curriculum_subject links for curriculum $CURRICULUM_ID.\n\n";

// Insert subjects
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
$currentGroup = '';

foreach ($curriculum as $row) {
    $group = $row['year'] . ' — ' . $row['sem'];
    if ($group !== $currentGroup) {
        echo "\n  ── $group ──\n";
        $currentGroup = $group;
    }

    $insertSubject->execute([
        'code'  => $row['code'],
        'name'  => $row['name'],
        'sem'   => $row['sem'],
        'year'  => $row['year'],
        'units' => $row['units'],
        'dept'  => $DEPARTMENT_ID,
    ]);

    $subjectId = (int) $pdo->lastInsertId();

    // Link to Psychology curriculum
    $insertLink->execute([
        'cid' => $CURRICULUM_ID,
        'sid' => $subjectId,
    ]);

    $inserted++;
    $totalUnits += $row['units'];
    echo "    [$inserted] {$row['code']} — {$row['name']} ({$row['units']} units) → ID: $subjectId\n";
}

echo "\n══════════════════════════════════════\n";
echo "  Total subjects inserted: $inserted\n";
echo "  Total units: $totalUnits\n";
echo "  Linked to PYSCHOLOGY Curriculum (ID: $CURRICULUM_ID)\n";
echo "  Department: PYSCHOLOGY (ID: $DEPARTMENT_ID)\n";
echo "══════════════════════════════════════\n";
echo "\nDone!\n";
