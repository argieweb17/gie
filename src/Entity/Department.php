<?php

namespace App\Entity;

use App\Repository\DepartmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartmentRepository::class)]
#[ORM\Table(name: 'department')]
class Department
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private string $departmentName;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $collegeName = null;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: User::class)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Subject::class)]
    private Collection $subjects;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->subjects = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getDepartmentName(): string { return $this->departmentName; }
    public function setDepartmentName(string $v): static { $this->departmentName = $v; return $this; }

    public function getCollegeName(): ?string { return $this->collegeName; }
    public function setCollegeName(?string $v): static { $this->collegeName = $v; return $this; }

    /** @return Collection<int, User> */
    public function getUsers(): Collection { return $this->users; }
    public function addUser(User $u): static { if (!$this->users->contains($u)) { $this->users->add($u); $u->setDepartment($this); } return $this; }
    public function removeUser(User $u): static { if ($this->users->removeElement($u) && $u->getDepartment() === $this) { $u->setDepartment(null); } return $this; }

    /** @return Collection<int, Subject> */
    public function getSubjects(): Collection { return $this->subjects; }
    public function addSubject(Subject $s): static { if (!$this->subjects->contains($s)) { $this->subjects->add($s); $s->setDepartment($this); } return $this; }
    public function removeSubject(Subject $s): static { if ($this->subjects->removeElement($s) && $s->getDepartment() === $this) { $s->setDepartment(null); } return $this; }

    public function __toString(): string { return $this->departmentName; }
}
