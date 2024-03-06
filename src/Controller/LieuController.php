<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lieu', name: 'lieu_')]
class LieuController extends AbstractController
{
    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {  
        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class, $lieu);

        $lieuForm->handleRequest($request);
        if ($lieuForm->isSubmitted() && $lieuForm->isValid()) {
            $lieu = $lieuForm->getData();

            $messageFlash = 'Lieu enregistré !';
                
            // Enregistrer l'entité en base de données
            $em->persist($lieu);
            $em->flush();

            
            // Afficher message flash
            $this->addFlash('success', $messageFlash);
            // Rediriger vers une autre page
            return $this->redirectToRoute('sortie_create');
        }

        return $this->render('lieu/create.html.twig', [
            'lieuForm' => $lieuForm
        ]);
    }
}
