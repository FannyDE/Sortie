<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LieuController extends AbstractController
{

    #[Route('/lieux/{villeId}', name: 'lieu_par_ville', methods: ['GET'])]
    public function getLieuxByVille(Request $request, LieuRepository $lieuRepository): JsonResponse
    {
        $villeId = $request->get('villeId');
        $lieux = $lieuRepository->findBy(['ville' => $villeId]);

        $lieuxData = [];
        foreach ($lieux as $lieu) {
            $lieuxData[] = [
                'id' => $lieu->getId(),
                'nom' => $lieu->getNom(),
            ];
        }

        return new JsonResponse($lieuxData);
    }


    #[Route('/lieu/{lieuId}', name: 'infos_lieu', methods: ['GET'])]
    public function infosLieu(Lieu $lieu, Request $request, LieuRepository $lieuRepository): JsonResponse
    {
        //$lieuId = $request->get('lieuId');
        //$lieu = $lieuRepository->find($lieuId);
        $data[] = [
            'rue' => $lieu->getRue(),
            'codePostal' => $lieu->getCodePostal(),
            'latitude' => $lieu->getLatitude(),
            'longitude' => $lieu->getLongitude(),
        ];
        
        return new JsonResponse($data);
    }
}
