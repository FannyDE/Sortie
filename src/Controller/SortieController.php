<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/sortie', name: 'sortie_')]
class   SortieController extends AbstractController
{
    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, VilleRepository $villeRepository, UserRepository $ur, EntityManagerInterface $em): Response
    {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $villes = $villeRepository->findAll();
        $organisateur = $ur->find(1);

        $sortieForm->handleRequest($request);
        if ($sortieForm->isSubmitted() 
        // && $sortieForm->isValid()
    ) {
            // Récupérer les données du formulaire
            $sortie = $sortieForm->getData();

            // Récupérer l'objet du lieu choisi
            $lieu = $sortie->getLieu();

            // Récupérer la donnée de l'état selon le bouton cliqué
            $etat = $sortie->getEtat();


            $sortie
                ->setLieu($lieu)
                ->setEtat($etat)
                ->setOrganisateur($organisateur);

            dd($sortie);
            // Enregistrer l'entité en base de données
            // $em->persist($sortie);
            // $em->flush();

            // Rediriger vers une autre page
            return $this->redirectToRoute('sortie_create');
        }

        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'villes' => $villes
        ]);
    }

}
