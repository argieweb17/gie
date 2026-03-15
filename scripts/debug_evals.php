<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db;charset=utf8mb4','root','');

echo "=== ALL EVALUATION PERIODS ===" . PHP_EOL;
$r = $pdo->query('SELECT id, evaluation_type, school_year, status, year_level, college, department_id, faculty, subject, start_date, end_date FROM evaluation_period ORDER BY id DESC LIMIT 10');
foreach ($r as $row) {
    echo "ID={$row['id']} | type={$row['evaluation_type']} | status={$row['status']} | yl={$row['year_level']} | col={$row['college']} | dept_id={$row['department_id']} | fac={$row['faculty']} | sub={$row['subject']} | {$row['start_date']} - {$row['end_date']}" . PHP_EOL;
}

echo PHP_EOL . "=== OPEN EVALS (status=1 AND now in range) ===" . PHP_EOL;
$r = $pdo->query('SELECT id, evaluation_type, year_level, college, department_id, faculty, subject FROM evaluation_period WHERE status=1 AND start_date <= NOW() AND end_date >= NOW()');
$count = 0;
foreach ($r as $row) {
    echo "ID={$row['id']} | type={$row['evaluation_type']} | yl={$row['year_level']} | col={$row['college']} | dept_id={$row['department_id']} | fac={$row['faculty']} | sub={$row['subject']}" . PHP_EOL;
    $count++;
}
if ($count === 0) echo "(none)" . PHP_EOL;

echo PHP_EOL . "=== STUDENT USERS ===" . PHP_EOL;
$r = $pdo->query("SELECT id, email, first_name, last_name, year_level, department_id, course_id, roles FROM user WHERE roles NOT LIKE '%ADMIN%' AND roles NOT LIKE '%FACULTY%' AND roles NOT LIKE '%STAFF%' LIMIT 5");
foreach ($r as $row) {
    echo "ID={$row['id']} | {$row['email']} | {$row['first_name']} {$row['last_name']} | yl={$row['year_level']} | dept={$row['department_id']} | course={$row['course_id']}" . PHP_EOL;
}

echo PHP_EOL . "=== DEPARTMENTS ===" . PHP_EOL;
$r = $pdo->query("SELECT id, department_name, college_name FROM department");
foreach ($r as $row) {
    echo "ID={$row['id']} | {$row['department_name']} | college={$row['college_name']}" . PHP_EOL;
}
