<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Ville;
use App\Entity\Campus;
use App\Entity\Sortie;
use App\Form\LieuType;
use App\Form\VilleType;
use App\Form\SortieType;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $repoVille = $em->getRepository(Ville::class);

        $campus = $repoCampus->find(1);
        $etat = $repoEtat->find(1);
        $villes = $repoVille->findAll();


        $sortie
            ->setCampus($campus)
            ->setEtat($etat);

        $ville = new Ville();
        $lieu = new Lieu();
        

        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $formVille = $this->createForm(VilleType::class, $ville);
        $formLieu = $this->createForm(LieuType::class, $lieu);

        $sortieForm->handleRequest($request);
        
        if($sortieForm->isSubmitted()){
            dd($request->get('villes_list'));
            return $this->redirectToRoute('home');
        }
        return $this->render("sortie/create.html.twig", [
            "sortieForm" => $sortieForm->createView(),
            "villes" => $villes,
            "formVille" => $formVille->createView(),
            "formLieu" => $formLieu->createView(),
        ]);
    }

    #[Route('/api/lieux', name: 'app_api_lieux_liste', methods: ['GET'])]
    public function liste(LieuRepository $lieuxRepository): JsonResponse
    {
        $lieux = $lieuxRepository->findAll();
        return $this->json(
            $lieux,
            Response::HTTP_OK,
            [],
            ['groups' => 'liste_lieux']
        );
    }
}
