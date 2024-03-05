<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/sortie', name: 'sortie_')]
class SortieController extends AbstractController
{
    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em, EtatRepository $er): Response
    {
        $user = $this->getUser();
        $isAdmin = $this->isGranted('ROLE_ADMIN');

        if ($user instanceof User) {
            $sortie = new Sortie();
            $ouverte = $er->find(1);
            $publiee = $er->find(2);
    
            // $organisateur = $ur->find(1);
            $campus = $user->getCampus();
    
            $sortie->setCampus($campus);
    
            $sortieForm = $this->createForm(SortieType::class, $sortie);
    
    
            $sortieForm->handleRequest($request);
            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) { 
                // Récupérer les données du formulaire
                $sortie = $sortieForm->getData();
    
                $sortie->setOrganisateur($user);
    
                if ($sortieForm->getClickedButton() === $sortieForm->get('annuler'))
                {
                    return $this->redirectToRoute('home');
                } else {
                    if ($sortieForm->getClickedButton() === $sortieForm->get('enregistrer')){
                        $sortie->setEtat($ouverte);
                        $messageFlash = 'Sortie enregistrée !';
                    } elseif ($sortieForm->getClickedButton() === $sortieForm->get('publier')) {
                        $sortie->setEtat($publiee);
                        $messageFlash = 'Sortie publiée !';
                    }
                    
                    // Enregistrer l'entité en base de données
                    $em->persist($sortie);
                    $em->flush();
    
                    $this->addFlash('success', $messageFlash);
                    // Rediriger vers une autre page
                    return $this->redirectToRoute('sortie_create');
                }
    
                
            }
    
            return $this->render('sortie/create.html.twig', [
                'sortieForm' => $sortieForm->createView(),
                'user' => $user
            ]);


        } else {return $this->redirectToRoute('app_login');}   
    }

}
