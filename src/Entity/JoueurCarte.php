<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\JoueurCarteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JoueurCarteRepository::class)]
#[ApiResource]
class JoueurCarte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'joueurCartes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Joueur $joueur = null;

    #[ORM\ManyToOne(inversedBy: 'joueurCartes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Carte $carte = null;

    #[ORM\Column]
    private ?int $quantite_cartes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJoueur(): ?Joueur
    {
        return $this->joueur;
    }

    public function setJoueur(?Joueur $joueur): self
    {
        $this->joueur = $joueur;

        return $this;
    }

    public function getCarte(): ?Carte
    {
        return $this->carte;
    }

    public function setCarte(?Carte $carte): self
    {
        $this->carte = $carte;

        return $this;
    }

    public function getQuantiteCartes(): ?int
    {
        return $this->quantite_cartes;
    }

    public function setQuantiteCartes(int $quantite_cartes): self
    {
        $this->quantite_cartes = $quantite_cartes;

        return $this;
    }
}
