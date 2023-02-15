<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CarteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: CarteRepository::class)]
#[UniqueEntity('nom')]
#[UniqueEntity('chiffre')]
#[Vich\Uploadable]
#[ApiResource]
class Carte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Vich\UploadableField(mapping: 'image_cartes', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column]
    private ?int $chiffre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'carte', targetEntity: JoueurCarte::class, orphanRemoval: true)]
    private Collection $joueurCartes;

    public function __construct()
    {
        $this->joueurCartes = new ArrayCollection();
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
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }


    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): self
    {
        $this->imageName = $imageName;

        return $this;
    }

    public function getChiffre(): ?int
    {
        return $this->chiffre;
    }

    public function setChiffre(int $chiffre): self
    {
        $this->chiffre = $chiffre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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
            $joueurCarte->setCarte($this);
        }

        return $this;
    }

    public function removeJoueurCarte(JoueurCarte $joueurCarte): self
    {
        if ($this->joueurCartes->removeElement($joueurCarte)) {
            // set the owning side to null (unless already changed)
            if ($joueurCarte->getCarte() === $this) {
                $joueurCarte->setCarte(null);
            }
        }

        return $this;
    }
}
