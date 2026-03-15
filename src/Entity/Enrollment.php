<?php

namespace App\Entity;

use App\Repository\EnrollmentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnrollmentRepository::class)]
#[ORM\UniqueConstraint(name: 'unique_enrollment', columns: ['student_id', 'subject_id'])]
class Enrollment
{
    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $student;

    #[ORM\ManyToOne(targetEntity: Subject::class, inversedBy: 'enrollments')]
    #[ORM\JoinColumn(nullable: false)]
    private Subject $subject;

    #[ORM\Column(length: 20, options: ['default' => 'pending'])]
    private string $status = self::STATUS_PENDING;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $section = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $schedule = null;

    public function getId(): ?int { return $this->id; }

    public function getStudent(): User { return $this->student; }
    public function setStudent(User $v): static { $this->student = $v; return $this; }

    public function getSubject(): Subject { return $this->subject; }
    public function setSubject(Subject $v): static { $this->subject = $v; return $this; }

    public function getStatus(): string { return $this->status; }
    public function setStatus(string $v): static { $this->status = $v; return $this; }

    public function getSection(): ?string { return $this->section; }
    public function setSection(?string $v): static { $this->section = $v !== null ? strtoupper(trim($v)) : null; return $this; }

    public function getSchedule(): ?string { return $this->schedule; }
    public function setSchedule(?string $v): static { $this->schedule = $v; return $this; }

    public function isPending(): bool { return $this->status === self::STATUS_PENDING; }
    public function isApproved(): bool { return $this->status === self::STATUS_APPROVED; }
    public function isRejected(): bool { return $this->status === self::STATUS_REJECTED; }
}
