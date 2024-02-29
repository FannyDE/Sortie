<?php

namespace App\Entity;

use App\Repository\CampusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampusRepository::class)]
class Campus
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;


    #[ORM\OneToMany(targetEntity:User::class, mappedBy:'campus')]

    private Collection $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
        $this->sorties = new ArrayCollection();
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->participants;
    }

    public function addUser(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setCampus($this);
        }

        return $this;
    }

    public function removeUser(User $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // Définissez le côté propriétaire sur null (sauf si déjà défini)
            if ($participant->getCampus() === $this) {
                $participant->setCampus(null);
            }
        }

        return $this;
    }

    #[ORM\OneToMany(targetEntity: Sortie::class, mappedBy: 'campus')]
    private Collection $sorties;


    public function __toString()
    {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSortie(Sortie $sortie): static
    {
        if (!$this->sorties->contains($sortie)) {
            $this->sorties->add($sortie);
            $sortie->setCampus($this);
        }

        return $this;
    }

    public function removeSortie(Sortie $sortie): static
    {
        if ($this->sorties->removeElement($sortie)) {
            // set the owning side to null (unless already changed)
            if ($sortie->getCampus() === $this) {
                $sortie->setCampus(null);
            }
        }

        return $this;
    }
}
