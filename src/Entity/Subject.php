<?php

namespace App\Entity;

use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubjectRepository::class)]
#[ORM\Table(name: 'subject')]
class Subject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private string $subjectCode;

    #[ORM\Column(length: 255)]
    private string $subjectName;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $faculty = null;

    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'subjects')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Department $department = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $semester = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $schoolYear = null;

    #[ORM\Column(nullable: true)]
    private ?int $units = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $yearLevel = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $term = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $section = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $room = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $schedule = null;

    #[ORM\ManyToMany(targetEntity: Curriculum::class, mappedBy: 'subjects')]
    private Collection $curricula;

    public function __construct()
    {
        $this->curricula = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getSubjectCode(): string { return $this->subjectCode; }
    public function setSubjectCode(string $v): static { $this->subjectCode = $v; return $this; }

    public function getSubjectName(): string { return $this->subjectName; }
    public function setSubjectName(string $v): static { $this->subjectName = $v; return $this; }

    public function getFaculty(): ?User { return $this->faculty; }
    public function setFaculty(?User $v): static { $this->faculty = $v; return $this; }

    public function getDepartment(): ?Department { return $this->department; }
    public function setDepartment(?Department $v): static { $this->department = $v; return $this; }

    public function getSemester(): ?string { return $this->semester; }
    public function setSemester(?string $v): static { $this->semester = $v; return $this; }

    public function getSchoolYear(): ?string { return $this->schoolYear; }
    public function setSchoolYear(?string $v): static { $this->schoolYear = $v; return $this; }

    public function getUnits(): ?int { return $this->units; }
    public function setUnits(?int $v): static { $this->units = $v; return $this; }

    public function getYearLevel(): ?string { return $this->yearLevel; }
    public function setYearLevel(?string $v): static { $this->yearLevel = $v; return $this; }

    public function getTerm(): ?string { return $this->term; }
    public function setTerm(?string $v): static { $this->term = $v; return $this; }

    public function getSection(): ?string { return $this->section; }
    public function setSection(?string $v): static { $this->section = $v !== null ? strtoupper(trim($v)) : null; return $this; }

    public function getRoom(): ?string { return $this->room; }
    public function setRoom(?string $v): static { $this->room = $v; return $this; }

    public function getSchedule(): ?string { return $this->schedule; }
    public function setSchedule(?string $v): static { $this->schedule = $v; return $this; }

    /** @return Collection<int, Curriculum> */
    public function getCurricula(): Collection { return $this->curricula; }
    public function addCurriculum(Curriculum $c): static { if (!$this->curricula->contains($c)) { $this->curricula->add($c); $c->addSubject($this); } return $this; }
    public function removeCurriculum(Curriculum $c): static { if ($this->curricula->removeElement($c)) { $c->removeSubject($this); } return $this; }

    public function __toString(): string { return $this->subjectCode . ' — ' . $this->subjectName; }
}
