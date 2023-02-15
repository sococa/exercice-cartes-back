<?php

namespace App\EntityListener;

use App\Entity\Joueur;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class JoueurListener{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function prePersist(Joueur $joueur)
    {
        $this->encodePassword($joueur);
    }

    public function preUpdate(Joueur $joueur)
    {
        $this->encodePassword($joueur);
    }

    /**
     * Encode password based on plain password
     *
     * @param Joueur $joueur
     * @return void
    */
    public function encodePassword(Joueur $joueur)
    {
        if($joueur->getPlainPassword() === null) {
            return;
        }

        $joueur->setPassword(
            $this->hasher->hashPassword(
                $joueur,
                $joueur->getPlainPassword()
            )
        );
        $joueur->setPlainPassword(null);
    }
}