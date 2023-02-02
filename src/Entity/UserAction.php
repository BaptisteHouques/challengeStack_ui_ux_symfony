<?php

namespace App\Entity;

use App\Repository\UserActionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserActionRepository::class)]
class UserAction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userActions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userActions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Action $action = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[ORM\JoinColumn(nullable: false)]
    private ?int $status = null;

    #[ORM\Column]
    #[ORM\JoinColumn(nullable: false)]
    private ?bool $is_responsible = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAction(): ?Action
    {
        return $this->action;
    }

    public function setAction(?Action $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function isIsResponsible(): ?bool
    {
        return $this->is_responsible;
    }

    public function setIsResponsible(bool $is_responsible): self
    {
        $this->is_responsible = $is_responsible;

        return $this;
    }
}
