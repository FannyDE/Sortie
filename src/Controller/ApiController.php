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

    /**
     * Récupère les informations d'un lieu
     * 
     * Rue, Code postal, Latitude et Longitude
     * @param Lieu $lieu l'instance de Lieu dont on veut les informations
     * @return JsonResponse $data objet avec les informations 
     */
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
    
    /**
     * Récupère les lieux d'une ville
     * 
     * Liste des lieux associés à une ville
     * @param int $villeId id de la ville dont on veut les lieux
     * @param LieuRepository $lr repository de l'entité Lieu
     * @return JsonResponse $lieuxArray objet avec les lieux (composé de leur id et de leur nom) 
     */
    #[Route('/lieux/{villeId}', name: 'lieux_par_ville', methods: ['GET','POST'])]
    public function lieuxParVille(int $villeId, LieuRepository $lr)
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
