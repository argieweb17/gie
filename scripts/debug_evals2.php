<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db;charset=utf8mb4','root','');

// Student user
$student = $pdo->query("SELECT id, email, year_level, department_id, course_id FROM user WHERE id=2")->fetch();
echo "Student: id={$student['id']}, yl={$student['year_level']}, dept={$student['department_id']}, course={$student['course_id']}" . PHP_EOL;

// Curricula matching student
echo PHP_EOL . "=== MATCHING CURRICULA ===" . PHP_EOL;
$r = $pdo->query("SELECT c.id, c.name, c.course_id, c.department_id FROM curriculum c WHERE c.course_id={$student['course_id']} OR c.department_id={$student['department_id']}");
$curIds = [];
foreach ($r as $row) {
    echo "Curriculum ID={$row['id']} | {$row['name']} | course_id={$row['course_id']} | dept_id={$row['department_id']}" . PHP_EOL;
    $curIds[] = $row['id'];
}

if (!empty($curIds)) {
    echo PHP_EOL . "=== CURRICULUM SUBJECTS (for matching curricula, student year level) ===" . PHP_EOL;
    // Check what the join table looks like
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables containing 'curriculum': " . implode(', ', array_filter($tables, fn($t) => stripos($t,'curriculum') !== false)) . PHP_EOL;
    
    // Check curriculum_subject join table
    $cols = $pdo->query("SHOW COLUMNS FROM curriculum")->fetchAll(PDO::FETCH_COLUMN);
    echo "curriculum columns: " . implode(', ', $cols) . PHP_EOL;

    // Check if there's a curriculum_subject table
    foreach ($tables as $t) {
        if (stripos($t, 'curriculum') !== false && $t !== 'curriculum') {
            echo "Extra table: $t" . PHP_EOL;
            $cols2 = $pdo->query("SHOW COLUMNS FROM `$t`")->fetchAll(PDO::FETCH_COLUMN);
            echo "  columns: " . implode(', ', $cols2) . PHP_EOL;
        }
    }
}

// Check subject table for faculty
echo PHP_EOL . "=== SUBJECTS WITH FACULTY (dept_id=1, limit 5) ===" . PHP_EOL;
$r = $pdo->query("SELECT s.id, s.subject_code, s.subject_name, s.year_level, s.faculty_id, s.department_id, s.schedule, s.section FROM subject s WHERE s.department_id=1 LIMIT 10");
foreach ($r as $row) {
    echo "ID={$row['id']} | {$row['subject_code']} - {$row['subject_name']} | yl={$row['year_level']} | faculty={$row['faculty_id']} | dept={$row['department_id']} | sched={$row['schedule']} | sec={$row['section']}" . PHP_EOL;
}
