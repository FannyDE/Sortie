<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\User;
use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

#[Route('/sortie', name: 'sortie_')]
class SortieController extends AbstractController
{
    /**
     * Créer une sortie
     * 
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        // Récupère l'instance de l'utilisateur (si elle existe)
        $user = $this->getUser();

        // Vérifie que l'instance de l'utilisateur est bien une instance de User et donc que l'utilisateur est bien connecté
        if ($user instanceof User) {
            $sortie = new Sortie();
            $ouverte = $em->getRepository(Etat::class)->find(1);
            $publiee = $em->getRepository(Etat::class)->find(2);
            $campus = $user->getCampus();
    
            $sortie->setCampus($campus);
    
            $sortieForm = $this->createForm(SortieType::class, $sortie);
    
            $sortieForm->handleRequest($request);
            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) { 
                // Récupérer les données du formulaire
                $sortie = $sortieForm->getData();
    
                $sortie->setOrganisateur($user);
    
                // Vérifie par quel bouton a été soumis le formulaire et met à jour l'entité Etat
                if ($sortieForm->getClickedButton() === $sortieForm->get('enregistrer')){
                    $sortie->setEtat($ouverte);
                    $messageFlash = 'Sortie enregistrée !';
                } elseif ($sortieForm->getClickedButton() === $sortieForm->get('publier')) {
                    $sortie->setEtat($publiee);
                    $messageFlash = 'Sortie publiée !';
                }
                
                // Enregistrer l'entité Sortie en base de données
                $em->persist($sortie);
                $em->flush();

                $this->addFlash('success', $messageFlash);
                // Rediriger vers une autre page
                return $this->redirectToRoute('sortie_create');
            }     
    
            return $this->render('sortie/create.html.twig', [
                'sortieForm' => $sortieForm->createView(),
                'user' => $user
            ]);

        } 
        // Sinon l'utilisateur n'est pas connecté et est donc redirigé vers la page de connexion
        else {return $this->redirectToRoute('app_login');}   
    }

    /**
     * Modifier une sortie
     * 
     * @param Sortie $sortie instance de Sortie
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(Sortie $sortie, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        // Vérifier si l'utilisateur actuel est bien l'organisateur de la sortie
        if ($user !== $sortie->getOrganisateur()) {
            throw new AccessDeniedHttpException("Vous n'êtes pas autorisé à modifier cette sortie.");
        }

        $ouverte = $em->getRepository(Etat::class)->find(1);
        $publiee = $em->getRepository(Etat::class)->find(2);
        
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        
        $sortieForm->handleRequest($request);
            
        if ($sortieForm->isSubmitted()) { 
            // Récupérer les données du formulaire
            $sortie = $sortieForm->getData();
            
            if ($sortieForm->getClickedButton() === $sortieForm->get('supprimer')) {
                $this->delete($sortie, $em);
            } elseif ($sortieForm->isValid()) {
                if ($sortieForm->getClickedButton() === $sortieForm->get('enregistrer')){
                    $sortie->setEtat($ouverte);
                    $messageFlash = 'Sortie modifiée enregistrée !';
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
        
        return $this->render('sortie/edit.html.twig', [
            'sortieForm' => $sortieForm,
            'sortie' => $sortie,
            'user' => $user
        ]); 
        
    }

    /**
     * Supprimer une sortie
     * 
     * @param Sortie $sortie instance de Sortie à supprimer
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(Sortie $sortie, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
    
        // Vérifier si l'utilisateur actuel est bien l'organisateur de la sortie
        if ($user !== $sortie->getOrganisateur()) {
            throw new AccessDeniedHttpException("Vous n'êtes pas autorisé à supprimer cette sortie.");
        }

        // Suppression de la sortie
        $em->remove($sortie);
        $em->flush();

        $this->addFlash('success', 'Sortie supprimée !');

        return $this->redirectToRoute('home');
    }

    /**
     * Annuler une sortie
     * 
     * @param Sortie $sortie instance de Sortie à annuler
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    // Fonction permettant d'annuler une sortie
    // TODO : vérifier la fonction et son fonctionnement
    #[Route('/{id}/cancel', name: 'cancel', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function cancel(Sortie $sortie, Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $sortieForm = $this->createForm(SortieType::class, $sortie);
        
        // TODO: Créer la logique de l'annulation de la sortie.
        $sortieForm->handleRequest($request);
            if ($sortieForm->isSubmitted() && $sortieForm->isValid()) { 
                // Récupérer les données du formulaire
                $sortie = $sortieForm->getData();
    
                $sortie->setOrganisateur($user);
            
                $messageFlash = 'Sortie annulée !';
                
                // Enregistrer l'entité en base de données
                $em->persist($sortie);
                $em->flush();

                $this->addFlash('success', $messageFlash);
                // Rediriger vers une autre page
                return $this->redirectToRoute('sortie_create');
            }   


        return $this->render('sortie/cancel.html.twig', [
            'sortieForm' => $sortieForm,
            'sortie' => $sortie,
            'user' => $user
        ]);
    }
}

