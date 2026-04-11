<?php

declare(strict_types=1);

use App\Entity\EvaluationPeriod;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

$projectDir = dirname(__DIR__);
if (file_exists($projectDir . '/.env')) {
    (new Dotenv())->bootEnv($projectDir . '/.env');
}

echo 'PHP_NOW=' . date('Y-m-d H:i:s P') . PHP_EOL;
echo 'PHP_TZ=' . date_default_timezone_get() . PHP_EOL;

$pdo = new PDO('mysql:host=127.0.0.1;dbname=evaluation_db;charset=utf8mb4', 'root', '');
$db = $pdo->query('SELECT NOW() as now_time, @@global.time_zone as global_tz, @@session.time_zone as session_tz')->fetch(PDO::FETCH_ASSOC);
echo 'DB_NOW=' . ($db['now_time'] ?? 'n/a') . PHP_EOL;
echo 'DB_GLOBAL_TZ=' . ($db['global_tz'] ?? 'n/a') . PHP_EOL;
echo 'DB_SESSION_TZ=' . ($db['session_tz'] ?? 'n/a') . PHP_EOL;

$kernel = new App\Kernel('dev', true);
$kernel->boot();
$repo = $kernel->getContainer()->get('doctrine')->getManager()->getRepository(EvaluationPeriod::class);
$open = $repo->findOpen('SET');

echo 'OPEN_SET=' . count($open) . PHP_EOL;
foreach ($open as $e) {
    echo 'OPEN_ID=' . $e->getId() . ' START=' . $e->getStartDate()->format('Y-m-d H:i:s') . ' END=' . $e->getEndDate()->format('Y-m-d H:i:s') . PHP_EOL;
}

$kernel->shutdown();
