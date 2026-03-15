<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
#[ORM\Table(name: 'course')]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $courseName;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getCourseName(): string { return $this->courseName; }
    public function setCourseName(string $v): static { $this->courseName = strtoupper($v); return $this; }

    /** @return Collection<int, User> */
    public function getUsers(): Collection { return $this->users; }
    public function addUser(User $u): static { if (!$this->users->contains($u)) { $this->users->add($u); $u->setCourse($this); } return $this; }
    public function removeUser(User $u): static { if ($this->users->removeElement($u) && $u->getCourse() === $this) { $u->setCourse(null); } return $this; }

    public function __toString(): string { return $this->courseName; }
}
