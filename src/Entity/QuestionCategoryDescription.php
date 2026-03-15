<?php

namespace App\Entity;

use App\Repository\QuestionCategoryDescriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionCategoryDescriptionRepository::class)]
#[ORM\UniqueConstraint(name: 'uq_cat_type', columns: ['category', 'evaluation_type'])]
class QuestionCategoryDescription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private string $category;

    #[ORM\Column(length: 10)]
    private string $evaluationType = 'SET';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int { return $this->id; }

    public function getCategory(): string { return $this->category; }
    public function setCategory(string $v): static { $this->category = $v; return $this; }

    public function getEvaluationType(): string { return $this->evaluationType; }
    public function setEvaluationType(string $v): static { $this->evaluationType = $v; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $v): static { $this->description = $v; return $this; }
}
