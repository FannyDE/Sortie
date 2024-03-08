<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
class Lieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['liste_lieux'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['liste_lieux'])]
    #[Assert\NotBlank(message: 'Veuillez renseigner le nom du lieu')]
    #[Assert\Length(max: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner la rue du lieu')]
    #[Assert\Length(max: 255)]
    private ?string $rue = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le code postal du lieu')]
    #[Assert\Length(exactly: 5,
                    exactMessage: 'Le code postal doit être composé de {{ limit }} caractères')]
    #[Assert\Regex(
                pattern: '#^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$#',
                message: 'Le code postal français doit respecter la forme : "DDDDD" où les "D" sont des chiffres, et dont les deux premiers chiffres vont de 01 à 98')]
    private ?string $codePostal = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez renseigner la latitude du lieu')]
    #[Assert\Length(max: 255)]
    #[Assert\Regex(
        pattern: '#^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$#',
        message: 'La latitude renseignée ne respecte pas le format attendu. Elle doit être comprise en -90 et 90.')]
    private ?float $latitude = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Veuillez renseigner la longitude du lieu')]
    #[Assert\Length(max: 255)]
    #[Assert\Regex(
        pattern: '#^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$#',
        message: 'La longitude renseignée ne respecte pas le format attendu. Elle doit être comprise en -180 et 180.')]
    private ?float $longitude = null;

    #[ORM\OneToMany(targetEntity: Sortie::class, mappedBy: 'lieu')]
    private Collection $sorties;

    #[ORM\ManyToOne(inversedBy: 'lieux')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['liste_lieux'])]
    private ?Ville $ville = null;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
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

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

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
            $sortie->setLieu($this);
        }

        return $this;
    }

    public function removeSortie(Sortie $sortie): static
    {
        if ($this->sorties->removeElement($sortie)) {
            // set the owning side to null (unless already changed)
            if ($sortie->getLieu() === $this) {
                $sortie->setLieu(null);
            }
        }

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
