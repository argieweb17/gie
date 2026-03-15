<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db;charset=utf8mb4','root','');

echo "=== CURRICULUM COLUMNS ===" . PHP_EOL;
$r = $pdo->query('SHOW COLUMNS FROM curriculum');
foreach ($r as $c) echo $c['Field'] . PHP_EOL;

echo PHP_EOL . "=== CURRICULA ===" . PHP_EOL;
$r = $pdo->query("SELECT * FROM curriculum LIMIT 5");
foreach ($r as $row) print_r($row);

echo PHP_EOL . "=== CURRICULA MATCHING student(course=1,dept=1) ===" . PHP_EOL;
$r = $pdo->query("SELECT id, course_id, department_id FROM curriculum WHERE course_id=1 OR department_id=1");
$curIds = [];
foreach ($r as $row) { 
    echo "ID={$row['id']} course={$row['course_id']} dept={$row['department_id']}" . PHP_EOL;
    $curIds[] = $row['id'];
}

// Find subjects linked to those curricula
echo PHP_EOL . "=== TABLES ===" . PHP_EOL;
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
$filtered = array_filter($tables, fn($t) => stripos($t,'curriculum') !== false || stripos($t,'subject') !== false);
echo implode(PHP_EOL, $filtered) . PHP_EOL;

// subject table
echo PHP_EOL . "=== SUBJECT COLUMNS ===" . PHP_EOL;
$r = $pdo->query('SHOW COLUMNS FROM subject');
foreach ($r as $c) echo $c['Field'] . PHP_EOL;

echo PHP_EOL . "=== SUBJECTS (dept=1, first 10) ===" . PHP_EOL;
$r = $pdo->query("SELECT id, subject_code, subject_name, year_level, faculty_id, curriculum_id FROM subject WHERE department_id=1 LIMIT 10");
foreach ($r as $row) {
    echo "ID={$row['id']} | {$row['subject_code']} - {$row['subject_name']} | yl={$row['year_level']} | fac={$row['faculty_id']} | cur={$row['curriculum_id']}" . PHP_EOL;
}
