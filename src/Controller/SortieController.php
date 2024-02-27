<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Campus;
use App\Entity\Sortie;
use App\Form\SortieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/sortie', name: 'sortie_')]
class   SortieController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $sortie = new Sortie();

        $repoCampus = $em->getRepository(Campus::class);
        $repoEtat = $em->getRepository(Etat::class);

        $campus = $repoCampus->find(1);
        $etat = $repoEtat->find(1);

        $sortie
            ->setCampus($campus)
            ->setEtat($etat);
        

        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted()){
            dd($sortie);
            return $this->redirectToRoute('home');
        }
        return $this->render("sortie/create.html.twig", [
            "sortieForm" => $sortieForm->createView()
        ]);
    }
}
