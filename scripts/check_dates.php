<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db','root','');
$r = $pdo->query('SELECT id, evaluation_type, status, start_date, end_date, year_level, college, department_id FROM evaluation_period WHERE status = 1');
echo "Today: ".date('Y-m-d H:i:s')."\n\n";
while($row = $r->fetch(PDO::FETCH_ASSOC)) {
    echo "ID={$row['id']} type={$row['evaluation_type']} dates={$row['start_date']} to {$row['end_date']} yearLevel={$row['year_level']} college={$row['college']} dept={$row['department_id']}\n";
    $now = new DateTime();
    $start = new DateTime($row['start_date']);
    $end = new DateTime($row['end_date']);
    echo "  In range: ".($start <= $now && $end >= $now ? 'YES' : 'NO')."\n";
}
