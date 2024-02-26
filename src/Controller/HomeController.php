<?php

namespace App\Controller;

use App\Entity\Sortie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController
{
    #[Route("/home", name: "home")]
    public function index()
    {
        return $this->render('home/index.html.twig');
    }

    public function search(): Response
    {

    }
}