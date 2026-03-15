<?php

namespace App\Entity;

use App\Repository\EvaluationPeriodRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\EvaluationResponse;

#[ORM\Entity(repositoryClass: EvaluationPeriodRepository::class)]
class EvaluationPeriod
{
    public const TYPE_SET = 'SET';
    public const TYPE_SEF = 'SEF';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private string $evaluationType = self::TYPE_SET; // SET or SEF

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $semester = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $schoolYear = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $startDate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $endDate;

    #[ORM\Column]
    private bool $status = true; // open or closed

    #[ORM\Column]
    private bool $anonymousMode = true;

    #[ORM\Column]
    private bool $resultsLocked = false;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $yearLevel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $college = null;

    #[ORM\ManyToOne(targetEntity: Department::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Department $department = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $faculty = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $subject = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $time = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $section = null;

    #[ORM\OneToMany(mappedBy: 'evaluationPeriod', targetEntity: EvaluationResponse::class, cascade: ['persist', 'remove'])]
    private Collection $responses;

    public function __construct()
    {
        $this->startDate = new \DateTime();
        $this->endDate = new \DateTime('+30 days');
        $this->responses = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getEvaluationType(): string { return $this->evaluationType; }
    public function setEvaluationType(string $v): static { $this->evaluationType = $v; return $this; }

    public function getSemester(): ?string { return $this->semester; }
    public function setSemester(?string $v): static { $this->semester = $v; return $this; }

    public function getSchoolYear(): ?string { return $this->schoolYear; }
    public function setSchoolYear(?string $v): static { $this->schoolYear = $v; return $this; }

    public function getStartDate(): \DateTimeInterface { return $this->startDate; }
    public function setStartDate(\DateTimeInterface $v): static { $this->startDate = $v; return $this; }

    public function getEndDate(): \DateTimeInterface { return $this->endDate; }
    public function setEndDate(\DateTimeInterface $v): static { $this->endDate = $v; return $this; }

    public function isStatus(): bool { return $this->status; }
    public function setStatus(bool $v): static { $this->status = $v; return $this; }

    public function isAnonymousMode(): bool { return $this->anonymousMode; }
    public function setAnonymousMode(bool $v): static { $this->anonymousMode = $v; return $this; }

    public function isResultsLocked(): bool { return $this->resultsLocked; }
    public function setResultsLocked(bool $v): static { $this->resultsLocked = $v; return $this; }

    public function getYearLevel(): ?string { return $this->yearLevel; }
    public function setYearLevel(?string $v): static { $this->yearLevel = $v; return $this; }

    public function getCollege(): ?string { return $this->college; }
    public function setCollege(?string $v): static { $this->college = $v; return $this; }

    public function getDepartment(): ?Department { return $this->department; }
    public function setDepartment(?Department $v): static { $this->department = $v; return $this; }

    public function getFaculty(): ?string { return $this->faculty; }
    public function setFaculty(?string $v): static { $this->faculty = $v; return $this; }

    public function getSubject(): ?string { return $this->subject; }
    public function setSubject(?string $v): static { $this->subject = $v; return $this; }

    public function getTime(): ?string { return $this->time; }
    public function setTime(?string $v): static { $this->time = $v; return $this; }

    public function getSection(): ?string { return $this->section; }
    public function setSection(?string $v): static { $this->section = $v !== null ? strtoupper(trim($v)) : null; return $this; }

    /** @return Collection<int, EvaluationResponse> */
    public function getResponses(): Collection { return $this->responses; }

    public function isOpen(): bool
    {
        $now = new \DateTime();
        return $this->status && $now >= $this->startDate && $now <= $this->endDate;
    }

    public function getLabel(): string
    {
        return $this->evaluationType . ' — ' . ($this->schoolYear ?? '');
    }
}
