<?php

namespace App\Controller;

use App\Entity\Joueur;
use App\Form\JoueurType;
use App\Repository\JoueurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

class JoueurController extends AbstractController
{
    #[Route('/joueurs', name: 'joueurs.index', methods: ['GET'])]
    public function index(JoueurRepository $repository, 
        Request $request): Response
    {

        $joueurs = $repository->findAll();

        return $this->render('joueur/index.html.twig', [
            'joueurs' => $joueurs
        ]);
    }

    #[Route('/joueurs/creer', name: 'joueurs.create', methods: ['GET', 'POST'])]
    public function new(Request $request, 
        EntityManagerInterface $manager) : Response
    {

        $joueur = new joueur();
        $joueur->setRoles(['ROLE_USER']);
        
        $form = $this->createForm(JoueurType::Class, $joueur);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $joueur = $form->getData();
            
            $this->addFlash(
                'success',
                'Joueur enregistrée avec succès' 
            );
            
            $manager->persist($joueur); /* "commit" : consigne d'envoi */
            $manager->flush();  /* "push" : execution */

            return $this->redirectToRoute('joueurs.index');
        }

        return $this->render('joueur/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/joueurs/modifier/{id}', name: 'joueurs.update', methods: ['GET', 'POST'])]
    public function edit(
        Joueur $joueur, 
        Request $request, 
        EntityManagerInterface $manager
    ) : Response {
        $form = $this->createForm(JoueurType::class, $joueur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $joueur = $form->getData();
            
            $manager->persist($joueur); /* "commit" : consigne d'envoi */
            $manager->flush();  /* "push" : execution */

            $this->addFlash(
                'success',
                'Joueur modifié'
            );

            return $this->redirectToRoute('joueurs.index');
        }

        return $this->render('joueur/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/joueurs/supprimer/{id}', name: 'joueurs.delete', methods: ['GET', 'POST', 'DELETE'])]
    public function delete(
        EntityManagerInterface $manager,
        Joueur $joueur
    ) : Response
    {
        if(!$joueur){
            $this->addFlash(
                'danger',
                'Joueur introuvable'
            );
            return $this->RedirectToRoute('joueurs.index');
        }

        $manager->remove($joueur);
        $manager->flush();

        $this->addFlash(
            'success',
            'Joueur supprimé'
        );

        return $this->RedirectToRoute('joueurs.index');
    }
}