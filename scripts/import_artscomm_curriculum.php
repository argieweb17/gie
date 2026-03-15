<?php
/**
 * Import ARTS AND COMMUNICATION Curriculum 2026 subjects
 * Curriculum ID: 16, Department ID: 13
 */

$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$curriculumId = 16;
$departmentId = 13;
$schoolYear   = '2026';

$subjects = [
    // ===== FIRST YEAR — 1st Semester =====
    ['code' => 'GE 1',       'name' => 'Understanding the Self',                                   'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'GE 2',       'name' => 'Readings in Philippine History',                            'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'GE 3',       'name' => 'The Contemporary World',                                    'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'FIL 1',      'name' => 'Akademiko sa Wikang Filipino',                              'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'PE 1',       'name' => 'Physical Fitness and Health',                                'units' => 2, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'NSTP 1',     'name' => 'National Service Training Program 1',                        'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],
    ['code' => 'MC 100',     'name' => 'Introduction to Communication Media',                        'units' => 3, 'year' => 'First Year', 'sem' => '1st Semester'],

    // ===== FIRST YEAR — 2nd Semester =====
    ['code' => 'GE 4',       'name' => 'Mathematics in the Modern World',                           'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'GE 5',       'name' => 'Purposive Communication',                                   'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'GE 6',       'name' => 'Art Appreciation',                                          'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'GE 7',       'name' => 'Science, Technology & Society',                              'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'GE 10',      'name' => 'Environmental Science',                                     'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'PE 2',       'name' => 'Recreational Games and Sports',                              'units' => 2, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'NSTP 2',     'name' => 'National Service Training Program 2',                        'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],
    ['code' => 'MC 101',     'name' => 'Media Law Journalism Principles',                            'units' => 3, 'year' => 'First Year', 'sem' => '2nd Semester'],

    // ===== FIRST YEAR — Enhancement =====
    ['code' => 'EN TRENDS',  'name' => 'Trends, Networks, and Critical Thinking in the 21st Century Culture', 'units' => 3, 'year' => 'First Year', 'sem' => 'Enhancement'],
    ['code' => 'EN COMM',    'name' => 'Community Engagement, Solidarity, and Citizenship',          'units' => 3, 'year' => 'First Year', 'sem' => 'Enhancement'],
    ['code' => 'EN DRRR',    'name' => 'Disaster Readiness and Risk Reduction',                      'units' => 3, 'year' => 'First Year', 'sem' => 'Enhancement'],
    ['code' => 'EN HUM',     'name' => 'Humanities',                                                 'units' => 3, 'year' => 'First Year', 'sem' => 'Enhancement'],

    // ===== SECOND YEAR — 1st Semester =====
    ['code' => 'GE 8',       'name' => 'Ethics',                                                    'units' => 3, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'GE 9',       'name' => 'Life and Works of Rizal',                                   'units' => 3, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'GE 11',      'name' => 'Gender and Society',                                        'units' => 3, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'LIT 1',      'name' => 'Philippine Literature',                                     'units' => 3, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'MC 200',     'name' => 'Communication Culture and Society',                          'units' => 3, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'MC 201',     'name' => 'Developmental Communication',                               'units' => 3, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'MC 202',     'name' => 'PR & Advertising',                                          'units' => 3, 'year' => 'Second Year', 'sem' => '1st Semester'],
    ['code' => 'PE 3',       'name' => 'Rhythmic and Social Recreation',                             'units' => 2, 'year' => 'Second Year', 'sem' => '1st Semester'],

    // ===== SECOND YEAR — 2nd Semester =====
    ['code' => 'GE 12',      'name' => 'Philippine Popular Culture',                                'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'FOR LANG',   'name' => 'Foreign Language',                                          'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'PE 4',       'name' => 'Cultural Presentation & Sports Management',                 'units' => 2, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'MC 203',     'name' => 'Communication Theory',                                      'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'MC 204',     'name' => 'PR, Opinion and News Writing',                              'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'MC 205',     'name' => 'Print Media & News Writing',                                'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'MC 206',     'name' => 'Writing Through Social Sciences/Philo',                     'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],
    ['code' => 'MC 207',     'name' => 'Public Relations Principles and Practices',                  'units' => 3, 'year' => 'Second Year', 'sem' => '2nd Semester'],

    // ===== THIRD YEAR — 1st Semester =====
    ['code' => 'MC 300',     'name' => 'Communication Research',                                    'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'MC 301',     'name' => 'Basic Communication',                                       'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'MC 302',     'name' => 'Visayan Journalism',                                        'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'MC 303',     'name' => 'Broadcast Journalism',                                      'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'MC 304',     'name' => 'Performance Media',                                         'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'MC 305',     'name' => 'Public Information',                                        'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'MC 306',     'name' => 'Micro Economics',                                           'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],
    ['code' => 'PSYCH 100',  'name' => 'Introduction to Psychology',                                'units' => 3, 'year' => 'Third Year', 'sem' => '1st Semester'],

    // ===== THIRD YEAR — 2nd Semester =====
    ['code' => 'MC 308',     'name' => 'Communication Media and Ethics/HR Mgt.',                    'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'MC 309',     'name' => 'Print Media Principles & Practices/Investigative Reporting','units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'MC 311',     'name' => 'Intro to Graphics & Photo Journalism',                      'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'MC 312',     'name' => 'Science & Health Communication',                            'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'MC 313',     'name' => 'Communication in the ASEAN Setting',                        'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],
    ['code' => 'MC 315',     'name' => 'Integrative Marketing Communication',                       'units' => 3, 'year' => 'Third Year', 'sem' => '2nd Semester'],

    // ===== THIRD YEAR — Summer =====
    ['code' => 'MC 316',     'name' => 'Internship',                                                'units' => 3, 'year' => 'Third Year', 'sem' => 'Summer'],

    // ===== FOURTH YEAR — 1st Semester =====
    ['code' => 'MC 400',     'name' => 'Thesis/Special Project 1',                                  'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'MC 401',     'name' => 'Communication Planning',                                    'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'MC 402',     'name' => 'News, Feature, Opinion, & Editorial Writings/Editing',      'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'MC 403',     'name' => 'Multimedia Story & Film Story Telling',                     'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],
    ['code' => 'BA 200',     'name' => 'Macroeconomics',                                            'units' => 3, 'year' => 'Fourth Year', 'sem' => '1st Semester'],

    // ===== FOURTH YEAR — 2nd Semester =====
    ['code' => 'MC 405',     'name' => 'Thesis/Special Project 2',                                  'units' => 3, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'MC 406',     'name' => 'Communication Management',                                  'units' => 3, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'MC 407',     'name' => 'Knowledge Management',                                      'units' => 3, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
    ['code' => 'MC 408',     'name' => 'Food, Travel & Online Journalism',                           'units' => 3, 'year' => 'Fourth Year', 'sem' => '2nd Semester'],
];

echo "Importing " . count($subjects) . " subjects for ARTS AND COMMUNICATION Curriculum 2026...\n\n";

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
echo "   Linked to ARTS AND COMMUNICATION Curriculum (ID: {$curriculumId}), Department (ID: {$departmentId})\n";
