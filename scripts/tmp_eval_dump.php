<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db;charset=utf8mb4', 'root', '');
$r = $pdo->query("SELECT id,evaluation_type,status,school_year,semester,year_level,college,department_id,faculty,subject,time,section,start_date,end_date FROM evaluation_period WHERE status=1 AND start_date<=NOW() AND end_date>=NOW() ORDER BY id");
foreach ($r as $row) {
    echo json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL;
}
