<?php

$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db;charset=utf8mb4', 'root', '');
$sql = "SELECT u.id,u.school_id,u.email,u.first_name,u.last_name,u.year_level,u.department_id,d.department_name,d.college_name,u.roles
        FROM user u
        LEFT JOIN department d ON d.id=u.department_id
        WHERE u.first_name LIKE 'Argie%' OR u.last_name LIKE 'pagbunocan%'
        ORDER BY u.id";

foreach ($pdo->query($sql) as $r) {
    echo implode(' | ', [
        $r['id'],
        $r['school_id'],
        $r['email'],
        $r['first_name'].' '.$r['last_name'],
        $r['year_level'],
        $r['department_id'],
        $r['department_name'],
        $r['college_name'],
        $r['roles'],
    ]) . PHP_EOL;
}
