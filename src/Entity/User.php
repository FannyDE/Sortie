<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?string $profilePicture = null;


    #[ORM\Column(nullable: true)]
    private ?string $pseudo = null;


    #[ORM\Column]
    private ?string $nom = null;

    #[ORM\Column]
    private ?string $prenom = null;

    #[ORM\Column]
    private ?string $tel = null;



    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\OneToMany(targetEntity: Sortie::class, mappedBy: 'organisateur')]
    private Collection $sortiesOrganisees;

    #[ORM\ManyToMany(targetEntity: Sortie::class, mappedBy: 'participants')]
    private Collection $sortiesParticipees;


    public function __construct()
    {
        $this->sortiesOrganisees = new ArrayCollection();
        $this->sortiesParticipees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(?string $profilePicture)
    {
        $this->profilePicture = $profilePicture;
        return $this;


    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
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
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }
    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortiesOrganisees(): Collection
    {
        return $this->sortiesOrganisees;
    }

    public function addSortieOrganisee(Sortie $sortie): static
    {
        if (!$this->sortiesOrganisees->contains($sortie)) {
            $this->sortiesOrganisees->add($sortie);
            $sortie->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortieOrganisee(Sortie $sortie): static
    {
        if ($this->sortiesOrganisees->removeElement($sortie)) {
            // set the owning side to null (unless already changed)
            if ($sortie->getOrganisateur() === $this) {
                $sortie->setOrganisateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortiesParticipees(): Collection
    {
        return $this->sortiesParticipees;
    }

    public function addSortieParticipee(Sortie $sortie): static
    {
        if (!$this->sortiesParticipees->contains($sortie)) {
            $this->sortiesParticipees->add($sortie);
            $sortie->addParticipant($this);
        }

        return $this;
    }

    public function removeSortieParticipee(Sortie $sortie): static
    {
        if ($this->sortiesParticipees->removeElement($sortie)) {
            $sortie->removeParticipant($this);
        }

        return $this;
    }


     #[ORM\ManyToOne(targetEntity:Campus::class, inversedBy: "participants")]
     #[ORM\JoinColumn(name:"campus_id", referencedColumnName:"id")]
     #[ORM\JoinColumn(nullable:false)]
     private Campus $campus;


     public function getCampus(): ? Campus
     {
       return $this->campus;
     }

     public function setCampus(Campus $campus): self
     {
       $this->campus = $campus;

       return $this;
     }

}

