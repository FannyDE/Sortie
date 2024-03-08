<?php

namespace App\Controller;

use App\Entity\Sortie;
use Doctrine\ORM\Mapping\Id;
use Doctrine\Persistence\ManagerRegistry;
use http\Env\Response;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route as RouteAlias;
use Symfony\Component\Routing\Attribute\Route;

class InscriptionController extends AbstractController
{
    #[Route("/inscription/{id}", name: "inscription_sortie")]

    public function inscriptionSortie(int $id) : \Symfony\Component\HttpFoundation\Response
    {
        // Récupérer la sortie à partir de l'identifiant

        $sortie = $this -> getDoctrine () -> getRepository ( Sortie::class ) -> find($id);

        // Inscrire l'utilisateur à la sortie (vous devrez implémenter cette fonctionnalité)
        $user = $this -> getUser ();
        $sortie -> addParticipant ( $user );

        // Enregistrer les modifications dans la base de données
        $entityManager = $this -> getDoctrine () -> getManager ();
        $entityManager -> flush ();

        $this-> addFlash ('success', 'Félicitation, vous êtes inscrit à la sortie, let\'s fun');

        return $this->redirectToRoute ('home');
    }

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    private function getDoctrine(): ManagerRegistry
    {
        return $this->doctrine;
    }
}