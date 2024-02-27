<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\User;
use App\Form\SearchSortieType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController
{
    #[Route("/home", name: "home")]
    public function index():Response
    {
        $user = $this->getUser();
        $sortie = new Sortie();
        $searchForm = $this->createForm(SearchSortieType::class);
        return $this->render('home/index.html.twig', [
            'user'=>$user,
            "searchForm" => $searchForm->createView()
        ]);
    }

    public function search(): Response
    {

        return $this->render("home/index.html.twig", [

        ]);
    }

    public function campus(): Response
    {
        $user = $this->getUser();

        $isAdmin = $this->isGranted('ROLE_ADMIN');


        $campusList = [];
        if ($isAdmin) {

            $campusList = $this->getDoctrine()->getRepository(Campus::class)->findAll();
        }


        return $this->render('home/index.html.twig', [
            'isAdmin' => $isAdmin,
            'campusList' => $campusList
        ]);
    }

}
