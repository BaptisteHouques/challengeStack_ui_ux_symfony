<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionRepository::class)]
class Action
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(nullable: true)]
    private ?int $max_user = null;

    #[ORM\OneToMany(mappedBy: 'action', targetEntity: UserAction::class)]
    private Collection $userActions;

    #[ORM\OneToMany(mappedBy: 'action', targetEntity: Ressource::class)]
    private Collection $ressources;

    #[ORM\ManyToOne(inversedBy: 'actions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ActionType $type = null;

    #[ORM\ManyToOne(inversedBy: 'actions_responsible')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $responsible = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    public function __construct()
    {
        $this->userActions = new ArrayCollection();
        $this->ressources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getMaxUser(): ?int
    {
        return $this->max_user;
    }

    public function setMaxUser(?int $max_user): self
    {
        $this->max_user = $max_user;

        return $this;
    }

    /**
     * @return Collection<int, UserAction>
     */
    public function getUserActions(): Collection
    {
        return $this->userActions;
    }

    public function addUserAction(UserAction $userAction): self
    {
        if (!$this->userActions->contains($userAction)) {
            $this->userActions->add($userAction);
            $userAction->setAction($this);
        }

        return $this;
    }

    public function removeUserAction(UserAction $userAction): self
    {
        if ($this->userActions->removeElement($userAction)) {
            // set the owning side to null (unless already changed)
            if ($userAction->getAction() === $this) {
                $userAction->setAction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ressource>
     */
    public function getRessources(): Collection
    {
        return $this->ressources;
    }

    public function addRessource(Ressource $ressource): self
    {
        if (!$this->ressources->contains($ressource)) {
            $this->ressources->add($ressource);
            $ressource->setAction($this);
        }

        return $this;
    }

    public function removeRessource(Ressource $ressource): self
    {
        if ($this->ressources->removeElement($ressource)) {
            // set the owning side to null (unless already changed)
            if ($ressource->getAction() === $this) {
                $ressource->setAction(null);
            }
        }

        return $this;
    }

    public function getType(): ?ActionType
    {
        return $this->type;
    }

    public function setType(?ActionType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getResponsible(): ?User
    {
        return $this->responsible;
    }

    public function setResponsible(?User $responsible): self
    {
        $this->responsible = $responsible;

    public function __toString(): string
    {
        return $this->libelle . " " . $this->libelle;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
