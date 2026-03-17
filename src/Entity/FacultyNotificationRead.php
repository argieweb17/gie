<?php

namespace App\Entity;

use App\Repository\FacultyNotificationReadRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FacultyNotificationReadRepository::class)]
#[ORM\UniqueConstraint(columns: ['user_id', 'evaluation_period_id'])]
class FacultyNotificationRead
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: EvaluationPeriod::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?EvaluationPeriod $evaluationPeriod = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $readAt = null;

    public function __construct()
    {
        $this->readAt = new \DateTimeImmutable();
    }

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?User { return $this->user; }
    public function setUser(?User $user): static { $this->user = $user; return $this; }
    public function getEvaluationPeriod(): ?EvaluationPeriod { return $this->evaluationPeriod; }
    public function setEvaluationPeriod(?EvaluationPeriod $ep): static { $this->evaluationPeriod = $ep; return $this; }
    public function getReadAt(): ?\DateTimeImmutable { return $this->readAt; }
}
