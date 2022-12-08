<?php

namespace App\Entity;

use App\Repository\AvantageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvantageRepository::class)]
class Avantage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column]
    private ?int $points = null;

    #[ORM\Column]
    private ?int $categorie = null;

    #[ORM\OneToMany(mappedBy: 'avantage', targetEntity: UserAvantage::class)]
    private Collection $userAvantages;

    public function __construct()
    {
        $this->userAvantages = new ArrayCollection();
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

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;

        return $this;
    }

    public function getCategorie(): ?int
    {
        return $this->categorie;
    }

    public function setCategorie(int $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, UserAvantage>
     */
    public function getUserAvantages(): Collection
    {
        return $this->userAvantages;
    }

    public function addUserAvantage(UserAvantage $userAvantage): self
    {
        if (!$this->userAvantages->contains($userAvantage)) {
            $this->userAvantages->add($userAvantage);
            $userAvantage->setAvantage($this);
        }

        return $this;
    }

    public function removeUserAvantage(UserAvantage $userAvantage): self
    {
        if ($this->userAvantages->removeElement($userAvantage)) {
            // set the owning side to null (unless already changed)
            if ($userAvantage->getAvantage() === $this) {
                $userAvantage->setAvantage(null);
            }
        }

        return $this;
    }
}
