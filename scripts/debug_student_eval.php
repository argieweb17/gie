<?php
$_SERVER['APP_ENV'] = 'dev';
$_SERVER['APP_DEBUG'] = '1';
$_ENV['DATABASE_URL'] = 'mysql://root:@127.0.0.1:3306/evaluation_db?serverVersion=8.3.14&charset=utf8mb4';
$_SERVER['DATABASE_URL'] = $_ENV['DATABASE_URL'];

require dirname(__DIR__).'/vendor/autoload.php';

$kernel = new App\Kernel('dev', true);
$kernel->boot();
$em = $kernel->getContainer()->get('doctrine')->getManager();

// Get student
$user = $em->getRepository(App\Entity\User::class)->findOneBy(['email' => 'a@gmail.com']);
echo "=== STUDENT ===\n";
echo "ID: ".$user->getId()."\n";
echo "Name: ".$user->getFullName()."\n";
echo "YearLevel: ".$user->getYearLevel()."\n";
echo "Dept: ".($user->getDepartment() ? $user->getDepartment()->getId().' - '.$user->getDepartment()->getDepartmentName() : 'null')."\n";
echo "College: ".($user->getDepartment() ? $user->getDepartment()->getCollegeName() : 'null')."\n";

echo "\n=== OPEN EVALS ===\n";
$evals = $em->getRepository(App\Entity\EvaluationPeriod::class)->findBy(['status' => true]);
foreach ($evals as $e) {
    echo "ID=".$e->getId()
        ." type=".$e->getEvaluationType()
        ." yearLevel=".($e->getYearLevel() ?? 'NULL')
        ." college=".($e->getCollege() ?? 'NULL')
        ." dept=".($e->getDepartment() ? $e->getDepartment()->getId() : 'NULL')
        ." faculty=".($e->getFaculty() ?? 'NULL')
        ." subject=".($e->getSubject() ?? 'NULL')
        ."\n";
}

echo "\n=== CURRICULUM SUBJECTS (dept match) ===\n";
$deptId = $user->getDepartment()?->getId();
$curricula = $em->getRepository(App\Entity\Curriculum::class)->findAll();
$count = 0;
$withFaculty = 0;
foreach ($curricula as $cur) {
    if ($deptId && $cur->getDepartment() && $cur->getDepartment()->getId() === $deptId) {
        echo "Curriculum: ".$cur->getCurriculumName()." dept=".$cur->getDepartment()->getId()."\n";
        foreach ($cur->getSubjects() as $s) {
            $count++;
            $f = $s->getFaculty();
            if ($f) {
                $withFaculty++;
                echo "  [HAS FACULTY] ".$s->getSubjectCode()." - ".$s->getSubjectName()." => ".$f->getFullName()." (year=".$s->getYearLevel().")\n";
            }
        }
    }
}
echo "Total subjects: $count, With faculty: $withFaculty\n";

// Now simulate the normalizeYearLevel function
function normalizeYearLevel(?string $yl): ?string {
    if ($yl === null) return null;
    $map = [
        '1st year' => 'First Year', 'first year' => 'First Year',
        '2nd year' => 'Second Year', 'second year' => 'Second Year',
        '3rd year' => 'Third Year', 'third year' => 'Third Year',
        '4th year' => 'Fourth Year', 'fourth year' => 'Fourth Year',
    ];
    return $map[strtolower(trim($yl))] ?? $yl;
}

echo "\n=== TARGETING CHECK ===\n";
$studentYL = normalizeYearLevel($user->getYearLevel());
echo "Student normalized YL: '$studentYL'\n";

foreach ($evals as $e) {
    if ($e->getEvaluationType() !== 'SET') {
        echo "Eval ".$e->getId().": SKIP (not SET)\n";
        continue;
    }
    
    // YL check
    if ($e->getYearLevel() !== null) {
        $evalYL = normalizeYearLevel($e->getYearLevel());
        echo "Eval ".$e->getId().": evalYL='$evalYL' vs studentYL='$studentYL'";
        if ($evalYL !== $studentYL) {
            echo " => FILTERED OUT by year level\n";
            continue;
        }
        echo " => PASS\n";
    } else {
        echo "Eval ".$e->getId().": yearLevel=NULL => PASS\n";
    }
    
    // Dept check
    if ($e->getDepartment() !== null) {
        $match = $user->getDepartment() !== null && $e->getDepartment()->getId() === $user->getDepartment()->getId();
        echo "  dept check: eval=".$e->getDepartment()->getId()." student=".($user->getDepartment()?->getId() ?? 'null')." => ".($match ? 'PASS' : 'FILTERED OUT')."\n";
        if (!$match) continue;
    } else {
        echo "  dept=NULL => PASS\n";
    }
    
    // College check
    if ($e->getCollege() !== null) {
        $studentCollege = $user->getDepartment()?->getCollegeName();
        $match = $studentCollege === $e->getCollege();
        echo "  college check: eval='".$e->getCollege()."' student='$studentCollege' => ".($match ? 'PASS' : 'FILTERED OUT')."\n";
        if (!$match) continue;
    } else {
        echo "  college=NULL => PASS\n";
    }
    
    echo "  => ELIGIBLE! Would show $withFaculty subjects with faculty\n";
}
