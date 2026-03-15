<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\AcademicYearRepository;
use App\Repository\FacultySubjectLoadRepository;
use App\Repository\SubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

#[AsEventListener(event: LoginSuccessEvent::class)]
class LoginListener
{
    public function __construct(
        private SubjectRepository $subjectRepo,
        private FacultySubjectLoadRepository $fslRepo,
        private AcademicYearRepository $ayRepo,
        private EntityManagerInterface $em,
    ) {}

    public function __invoke(LoginSuccessEvent $event): void
    {
        $currentAY = $this->ayRepo->findCurrent();
        $ayEnded = $currentAY && $currentAY->getEndDate() && new \DateTime() > $currentAY->getEndDate();

        // Only reset all faculty assignments if the academic year has ended
        if ($ayEnded) {
            $subjects = $this->subjectRepo->findAll();
            foreach ($subjects as $subject) {
                if ($subject->getFaculty() !== null) {
                    $subject->setFaculty(null);
                    $subject->setSchedule(null);
                    $subject->setSection(null);
                }
            }
            $this->em->flush();
        }

        // If the logging-in user is faculty, restore their saved loads
        $user = $event->getUser();
        if (!$user instanceof User || !in_array('ROLE_FACULTY', $user->getRoles())) {
            return;
        }

        $savedLoads = $this->fslRepo->findByFacultyAndAcademicYear(
            $user->getId(),
            $currentAY?->getId()
        );

        foreach ($savedLoads as $fsl) {
            $subject = $fsl->getSubject();
            // Only restore if not already assigned to another faculty
            if ($subject->getFaculty() === null) {
                $subject->setFaculty($user);
                $subject->setSection($fsl->getSection());
                $subject->setSchedule($fsl->getSchedule());
            }
        }
        $this->em->flush();
    }
}
