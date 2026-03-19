<?php

namespace App\Entity;

use App\Repository\MessageNotificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageNotificationRepository::class)]
class MessageNotification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?User $notifiedUser = null;

    #[ORM\ManyToOne(targetEntity: EvaluationMessage::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?EvaluationMessage $message = null;

    #[ORM\Column]
    private bool $isRead = false;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $readAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }

    public function getNotifiedUser(): ?User { return $this->notifiedUser; }
    public function setNotifiedUser(?User $user): static { $this->notifiedUser = $user; return $this; }

    public function getMessage(): ?EvaluationMessage { return $this->message; }
    public function setMessage(?EvaluationMessage $message): static { $this->message = $message; return $this; }

    public function isRead(): bool { return $this->isRead; }
    public function setIsRead(bool $isRead): static { $this->isRead = $isRead; return $this; }

    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function getReadAt(): ?\DateTimeInterface { return $this->readAt; }
    public function setReadAt(?\DateTimeInterface $readAt): static { $this->readAt = $readAt; return $this; }

    public function markAsRead(): static
    {
        $this->isRead = true;
        $this->readAt = new \DateTime();
        return $this;
    }
}
