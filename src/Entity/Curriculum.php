<?php

namespace App\Entity;

use App\Repository\CurriculumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CurriculumRepository::class)]
#[ORM\Table(name: 'curriculum')]
class Curriculum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $curriculumName;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $curriculumYear = null;

    #[ORM\ManyToOne(targetEntity: Course::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Course $course = null;

    #[ORM\ManyToOne(targetEntity: Department::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Department $department = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Subject::class, inversedBy: 'curricula')]
    #[ORM\JoinTable(name: 'curriculum_subject')]
    private Collection $subjects;

    public function __construct()
    {
        $this->subjects = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getCurriculumName(): string { return $this->curriculumName; }
    public function setCurriculumName(string $v): static { $this->curriculumName = $v; return $this; }

    public function getCurriculumYear(): ?string { return $this->curriculumYear; }
    public function setCurriculumYear(?string $v): static { $this->curriculumYear = $v; return $this; }

    public function getCourse(): ?Course { return $this->course; }
    public function setCourse(?Course $v): static { $this->course = $v; return $this; }

    public function getDepartment(): ?Department { return $this->department; }
    public function setDepartment(?Department $v): static { $this->department = $v; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $v): static { $this->description = $v; return $this; }

    /** @return Collection<int, Subject> */
    public function getSubjects(): Collection { return $this->subjects; }
    public function addSubject(Subject $s): static { if (!$this->subjects->contains($s)) { $this->subjects->add($s); } return $this; }
    public function removeSubject(Subject $s): static { $this->subjects->removeElement($s); return $this; }

    public function __toString(): string { return $this->curriculumName; }
}
