<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LieuController extends AbstractController
{
    #[Route('/lieux/{ville}', name: 'lieu_par_ville', methods: ['GET'])]
    public function lieuxParVille(Ville $ville, LieuRepository $lieuRepository): JsonResponse
    {
        $lieux = $lieuRepository->findBy(['ville' => $ville]);

        $data = [];
        foreach ($lieux as $lieu) {
            $data[] = [
                'id' => $lieu->getId(),
                'nom' => $lieu->getNom(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route('/lieu/{nomLieu}', name: 'infos_lieu', methods: ['GET'])]
    public function infosLieu(Lieu $nomLieu, LieuRepository $lieuRepository): JsonResponse
    {
        $lieu = $lieuRepository->findOneBy(['nom' => $nomLieu->getNom()]);

        $data[] = [
            'rue' => $lieu->getRue(),
            'codePostal' => $lieu->getCodePostal(),
            'latitude' => $lieu->getLatitude(),
            'longitude' => $lieu->getLongitude(),
        ];
    
        return new JsonResponse($data);
    }
}
