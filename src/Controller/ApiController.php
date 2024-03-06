<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Repository\LieuRepository;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api', name: 'api_', methods: ['GET'])]
class ApiController extends AbstractController
{

    #[Route('/lieu/{id}', name: 'infos_lieu', methods: ['GET'])]
    public function infosLieu(Lieu $lieu): JsonResponse
    {
        $data[] = [
            'rue' => $lieu->getRue(),
            'codePostal' => $lieu->getCodePostal(),
            'latitude' => $lieu->getLatitude(),
            'longitude' => $lieu->getLongitude(),
        ];
        
        return new JsonResponse($data);
    }
// A corriger
    #[Route('/lieux/{villeId}', name: 'lieux_par_ville', methods: ['GET','POST'])]
    public function lieuxParVille($villeId, LieuRepository $lr)
    {
        // Récupérer les lieux en fonction de l'ID de la ville
        $lieux = $lr->findBy(['ville' => $villeId]);

        // Convertir les lieux en un tableau associatif
        $lieuxArray = [];
        if ($villeId) {
            foreach ($lieux as $lieu) {
                $lieuxArray[] = [
                    'id' => $lieu->getId(),
                    'nom' => $lieu->getNom()
                ];
            }
        }

        // Retourner une JsonResponse avec les lieux
        return new JsonResponse($lieuxArray);
    }

}
