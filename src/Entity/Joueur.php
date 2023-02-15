<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\JoueurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('email')]
#[UniqueEntity('nom')]
#[ORM\Entity(repositoryClass: JoueurRepository::class)]
#[ORM\EntityListeners(['App\EntityListener\JoueurListener'])]
#[ApiResource]
class Joueur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email()]
    #[Assert\Length(min: 2, max: 180)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    private ?string $plainPassword = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank()]
    private ?string $password = 'password';


    #[ORM\OneToMany(mappedBy: 'joueur', targetEntity: JoueurCarte::class, orphanRemoval: true)]
    private Collection $joueurCartes;

    public function __construct()
    {
        $this->joueurCartes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

 
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
     * @return Collection<int, JoueurCarte>
     */
    public function getJoueurCartes(): Collection
    {
        return $this->joueurCartes;
    }

    public function addJoueurCarte(JoueurCarte $joueurCarte): self
    {
        if (!$this->joueurCartes->contains($joueurCarte)) {
            $this->joueurCartes->add($joueurCarte);
            $joueurCarte->setJoueur($this);
        }

        return $this;
    }

    public function removeJoueurCarte(JoueurCarte $joueurCarte): self
    {
        if ($this->joueurCartes->removeElement($joueurCarte)) {
            // set the owning side to null (unless already changed)
            if ($joueurCarte->getJoueur() === $this) {
                $joueurCarte->setJoueur(null);
            }
        }

        return $this;
    }
}
