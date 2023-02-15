<?php

namespace App\Controller;

use App\Entity\Carte;
use App\Form\CarteType;
use App\Form\AddCarteType;
use App\Repository\CarteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

class CarteController extends AbstractController
{
    #[Route('/cartes', name: 'cartes.index', methods: ['GET'])]
    public function index(CarteRepository $repository, 
        Request $request): Response
    {

        $cartes = $repository->findAll();
         
        return $this->render('carte/index.html.twig', [
            'cartes' => $cartes
        ]);
    }

    #[Route('/cartes/creer', name: 'cartes.create', methods: ['GET', 'POST'])]
    public function new(Request $request, 
        EntityManagerInterface $manager) : Response
    {

        $carte = new Carte();
        $form = $this->createForm(CarteType::Class, $carte);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $carte = $form->getData();
            
            $manager->persist($carte); /* "commit" : consigne d'envoi */
            $manager->flush();  /* "push" : execution */

            $this->addFlash(
                'success',
                'Votre carte est enregistrée'
            );

            return $this->redirectToRoute('cartes.index');
        }

        return $this->render('carte/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/cartes/modifier/{id}', name: 'cartes.update', methods: ['GET', 'POST'])]
    public function edit(Carte $carte, Request $request,  EntityManagerInterface $manager) : Response {
        $form = $this->createForm(CarteType::class, $carte);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $carte = $form->getData();
            
            $manager->persist($carte); /* "commit" : consigne d'envoi */
            $manager->flush();  /* "push" : execution */

            $this->addFlash(
                'success',
                'Votre carte est modifiée'
            );

            return $this->redirectToRoute('cartes.index');
        }

        return $this->render('carte/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/cartes/supprimer/{id}', name: 'cartes.delete', methods: ['GET', 'POST'])]
    public function delete(EntityManagerInterface $manager, Carte $carte) : Response
    {
        if(!$carte){
            $this->addFlash(
                'danger',
                'Carte introuvable'
            );
            return $this->RedirectToRoute('cartes.index');
        }

        $manager->remove($carte);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre carte est supprimée'
        );

        return $this->RedirectToRoute('cartes.index');
    }

}
