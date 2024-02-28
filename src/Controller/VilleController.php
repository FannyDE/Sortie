<?php

namespace App\Controller;

use App\Form\VilleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VilleController extends AbstractController
{
    #[Route('/ville', name: 'app_ville', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $formVille = $this->createForm(VilleType::class);
        $formVille->handleRequest($request);

        if ($formVille->isSubmitted() && $formVille->isValid()) {
            // handle form submission
        }

        return $this->render('ville/index.html.twig', [
            'formVille' => $formVille->createView(),
        ]);
    }
}