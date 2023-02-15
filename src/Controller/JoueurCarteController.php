<?php

namespace App\Controller;

use App\Entity\Carte;
use App\Entity\Joueur;
use App\Entity\JoueurCarte;
use App\Form\JoueurCarteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\JoueurCarteRepository;
use App\Repository\CarteRepository;
use Symfony\Component\HttpFoundation\JsonResponse;


class JoueurCarteController extends AbstractController
{
    #[Route("/associer", name:"association.create")]
    public function associate(Request $request, EntityManagerInterface $entityManager, JoueurCarteRepository $repository): Response
    {
        $form = $this->createForm(JoueurCarteType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $joueur = $data['joueur'];
            $carte = $data['carte'];
            $quantite = $data['quantite'];

            $association = $repository->findOneBy(['joueur' => $joueur, 'carte' => $carte]);

            if ($association) {
                $quantite += $association->getQuantiteCartes();
                $association->setQuantiteCartes($quantite);
            } else {
                $joueurCarte = new JoueurCarte();
                $joueurCarte->setJoueur($joueur);
                $joueurCarte->setCarte($carte);
                $joueurCarte->setQuantiteCartes($quantite);

                $entityManager->persist($joueurCarte);
            }
    

            $entityManager->flush();

            $this->addFlash('success', 'Carte associée avec succès.');

            return $this->redirectToRoute('associations.index');
        }

        return $this->render('joueur_carte/associate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/associations", name:"associations.index")]
    public function displayAssociations(JoueurCarteRepository $repository)
    {
        $associations = $repository->findAll();

        $groupedAssociations = [];

        foreach ($associations as $association) {
            $joueurId = $association->getJoueur()->getId();
            if (!isset($groupedAssociations[$joueurId])) {
                $groupedAssociations[$joueurId] = [
                    'joueur' => $association->getJoueur(),
                    'associations' => [],
                ];
            }
            $groupedAssociations[$joueurId]['associations'][] = $association;
        }

        return $this->render('joueur_carte/index.html.twig', [
            'groupedAssociations' => $groupedAssociations,
        ]);
    }


    #[Route('/associations/supprimer/{id}', name: 'associations.delete', methods: ['GET', 'POST'])]
    public function delete(
        EntityManagerInterface $manager,JoueurCarte $joueurcarte) : Response
    {
        if(!$joueurcarte){
            $this->addFlash(
                'danger',
                'Association introuvable'
            );
            return $this->RedirectToRoute('associations.index');
        }

        $manager->remove($joueurcarte);
        $manager->flush();

        $this->addFlash(
            'success',
            'Association supprimée'
        );

        return $this->RedirectToRoute('associations.index');
    }

    
    #[Route("/api/cartesJoueurAssociations", name:"api.associations")]
    public function countCartesWithRelation(JoueurCarteRepository $joueurCarteRepository): JsonResponse
    {
        $cartes = $joueurCarteRepository->countByCartesWithRelation();
        return $this->json($cartes);
    }

    private function getJoueursFromCarte(Carte $carte)
    {
        $joueurs = [];
        foreach ($carte->getJoueurCartes() as $joueurCarte) {
            $joueur = $joueurCarte->getJoueur();
            $joueurs[] = [
                'id' => $joueur->getId(),
                'nom' => $joueur->getNom(),
                'email' => $joueur->getEmail(),
            ];
        }
        return $joueurs;
    }
}