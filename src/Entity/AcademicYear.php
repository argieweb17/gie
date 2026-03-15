<?php

namespace App\Entity;

use App\Repository\AcademicYearRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcademicYearRepository::class)]
#[ORM\Table(name: 'academic_year')]
class AcademicYear
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private string $yearLabel; // e.g. "2025-2026"

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $semester = null; // e.g. "1st Semester", "2nd Semester", "Summer"

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\Column]
    private bool $isCurrent = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }

    public function getYearLabel(): string { return $this->yearLabel; }
    public function setYearLabel(string $v): static { $this->yearLabel = $v; return $this; }

    public function getSemester(): ?string { return $this->semester; }
    public function setSemester(?string $v): static { $this->semester = $v; return $this; }

    public function getStartDate(): ?\DateTimeInterface { return $this->startDate; }
    public function setStartDate(?\DateTimeInterface $v): static { $this->startDate = $v; return $this; }

    public function getEndDate(): ?\DateTimeInterface { return $this->endDate; }
    public function setEndDate(?\DateTimeInterface $v): static { $this->endDate = $v; return $this; }

    public function isCurrent(): bool { return $this->isCurrent; }
    public function setIsCurrent(bool $v): static { $this->isCurrent = $v; return $this; }

    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }

    /**
     * Display label: "2025-2026 — 1st Semester"
     */
    public function getLabel(): string
    {
        $label = $this->yearLabel;
        if ($this->semester) {
            $label .= ' — ' . $this->semester;
        }
        return $label;
    }

    public function __toString(): string
    {
        return $this->getLabel();
    }
}
