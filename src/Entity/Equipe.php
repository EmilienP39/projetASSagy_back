<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'equipe', targetEntity: User::class)]
    private Collection $users;

    #[ORM\Column]
    private ?bool $isSenior = null;

    #[ORM\Column]
    private ?float $cotisation_base = null;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setEquipe($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getEquipe() === $this) {
                $user->setEquipe(null);
            }
        }

        return $this;
    }

    public function isIsSenior(): ?bool
    {
        return $this->isSenior;
    }

    public function setIsSenior(bool $isSenior): self
    {
        $this->isSenior = $isSenior;

        return $this;
    }

    public function getCotisationBase(): ?float
    {
        return $this->cotisation_base;
    }

    public function setCotisationBase(float $cotisation_base): self
    {
        $this->cotisation_base = $cotisation_base;

        return $this;
    }
}
