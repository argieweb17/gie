<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db;charset=utf8mb4','root','');

echo "=== curriculum_subject COLUMNS ===" . PHP_EOL;
$r = $pdo->query('SHOW COLUMNS FROM curriculum_subject');
foreach ($r as $c) echo $c['Field'] . PHP_EOL;

echo PHP_EOL . "=== Subjects in BSIT curriculum (id=7) ===" . PHP_EOL;
$r = $pdo->query("SELECT cs.*, s.subject_code, s.subject_name, s.year_level, s.faculty_id FROM curriculum_subject cs JOIN subject s ON cs.subject_id=s.id WHERE cs.curriculum_id=7");
$count = 0;
foreach ($r as $row) {
    echo "subj_id={$row['subject_id']} | {$row['subject_code']} - {$row['subject_name']} | yl={$row['year_level']} | fac_id={$row['faculty_id']}" . PHP_EOL;
    $count++;
}
echo "Total: $count subjects" . PHP_EOL;

// Now check subjects that match student's year level (4th Year)
echo PHP_EOL . "=== 4th Year subjects in BSIT curriculum ===" . PHP_EOL;
$r = $pdo->query("SELECT cs.*, s.subject_code, s.subject_name, s.year_level, s.faculty_id FROM curriculum_subject cs JOIN subject s ON cs.subject_id=s.id WHERE cs.curriculum_id=7 AND (s.year_level LIKE '%4th%' OR s.year_level LIKE '%Fourth%')");
$count = 0;
foreach ($r as $row) {
    echo "subj_id={$row['subject_id']} | {$row['subject_code']} - {$row['subject_name']} | yl={$row['year_level']} | fac_id={$row['faculty_id']}" . PHP_EOL;
    $count++;
}
echo "Total 4th year: $count" . PHP_EOL;

// Check if any subjects have faculty assigned
echo PHP_EOL . "=== Subjects WITH faculty assigned in BSIT curriculum ===" . PHP_EOL;
$r = $pdo->query("SELECT cs.*, s.subject_code, s.subject_name, s.year_level, s.faculty_id, u.first_name, u.last_name FROM curriculum_subject cs JOIN subject s ON cs.subject_id=s.id LEFT JOIN user u ON s.faculty_id=u.id WHERE cs.curriculum_id=7 AND s.faculty_id IS NOT NULL");
$count = 0;
foreach ($r as $row) {
    echo "subj_id={$row['subject_id']} | {$row['subject_code']} - {$row['subject_name']} | yl={$row['year_level']} | faculty={$row['first_name']} {$row['last_name']}" . PHP_EOL;
    $count++;
}
echo "Total with faculty: $count" . PHP_EOL;

// The evaluation period
echo PHP_EOL . "=== EVAL PERIOD ID=13 ===" . PHP_EOL;
$r = $pdo->query("SELECT * FROM evaluation_period WHERE id=13");
foreach ($r as $row) print_r($row);
