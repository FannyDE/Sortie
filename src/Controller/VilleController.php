<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ville', name: 'ville_', methods: ['GET', 'POST'])]
class VilleController extends AbstractController
{
    #[Route('/', name: 'list', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $villes = $em->getRepository(Ville::class)->findAll();

        return $this->render('ville/index.html.twig', [
            'villes' => $villes
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $ville = new Ville();
        $villeForm = $this->createForm(VilleType::class);
        
        $villeForm->handleRequest($request);
        if ($villeForm->isSubmitted() && $villeForm->isValid()) {
            $ville = $villeForm->getData();

            $messageFlash = 'Ville enregistrée !';
                
            // Enregistrer l'entité en base de données
            $em->persist($ville);
            $em->flush();

            
            // Afficher message flash
            $this->addFlash('success', $messageFlash);
            // Rediriger vers une autre page
            return $this->redirectToRoute('sortie_create');
        }

        return $this->render('ville/create.html.twig', [
            'villeForm' => $villeForm
        ]);
    }
}