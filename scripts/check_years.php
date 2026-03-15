<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db', 'root', '');
$r = $pdo->query('SELECT DISTINCT year_level FROM subject ORDER BY year_level');
while ($row = $r->fetch(PDO::FETCH_NUM)) {
    echo var_export($row[0], true) . "\n";
}
