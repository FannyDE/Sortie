<?php

namespace App\Controller;

use App\DTO\SearchDTO;
use App\Entity\Campus;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\SearchSortieType;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;



class HomeController extends AbstractController
{
    #[Route("/home", name: "home")]
    public function index(Request $request): Response

    {
        $user = $this->getUser();
        $isAdmin = $this->isGranted('ROLE_ADMIN');




            /*$campusEntities = $this->getDoctrine()->getRepository(Campus::class)->findAll();
            $campusList = [];

            foreach ($campusEntities as $campus) {
                // Pour chaque entité Campus récupérée, vous pouvez ajouter son nom à votre liste
                $campusList[$campus->getNom()] = $campus->getId() ;
            }*/

            // Créer le formulaire de recherche de sortie
            $searchDTO = new SearchDTO();
            $searchForm = $this -> createForm ( SearchSortieType::class, $searchDTO);

                $searchForm -> handleRequest ( $request );

                // Initialiser les résultats de recherche à null
                $resultats = null;

                if ($searchForm -> isSubmitted () && $searchForm -> isValid ()) {

                    /* Construire un objet SearchDTO avec les données du formulaire
                    $searchDTO = new SearchDTO(
                        $searchForm->get('campus')->getData(),
                        $searchForm->get('search')->getData(),
                        \DateTime::createFromFormat('Y-m-d', $searchForm->get('startDate')->getData()),
                        \DateTime::createFromFormat('Y-m-d', $searchForm->get('endDate')->getData()),
                        (bool) $searchForm->get('organizer')->getData(),
                        (bool) $searchForm->get('registered')->getData(),
                        (bool) $searchForm->get('notRegistered')->getData(),
                        (bool) $searchForm->get('pastEvents')->getData()
                    );*/

                    // Utiliser le SearchDTO pour effectuer une recherche en base de données
                    $repository = $this -> getDoctrine () -> getRepository ( Sortie::class );
                    $queryBuilder = $repository -> createQueryBuilder ( 's' );

                    // Ajouter les conditions de recherche en fonction des valeurs dans le DTO

                    if ($searchDTO -> getSearch ()) {
                        $queryBuilder -> andWhere ( 's.nom LIKE :search' )
                            -> setParameter ( 'search', '%' . $searchDTO -> getSearch () . '%' );
                    }
                    if ($searchDTO -> getCampus ()) {
                        $queryBuilder -> andWhere ( 's.campus = :campus' )
                            -> setParameter ( 'campus', $searchDTO -> getCampus () );
                    }
                    if ($searchDTO -> getStartDate ()) {
                        $queryBuilder -> andWhere ( 's.dateHeureDebut >= :startDate' )
                            -> setParameter ( 'startDate', $searchDTO -> getStartDate () );
                    }

                    if ($searchDTO -> getEndDate ()) {
                        $queryBuilder -> andWhere ( 's.dateHeureDebut <= :endDate' )
                            -> setParameter ( 'endDate', $searchDTO -> getEndDate () );
                    }

                    if ($searchDTO -> getOrganizer ()) {
                        $queryBuilder -> andWhere ( 's.organisateur = :organisateur' )
                            -> setParameter ( 'organisateur', $user -> getId () );
                    }

                    // Supposons que vous ayez une relation entre l'entité Sortie et l'entité Participant
                    // et que vous ayez un moyen de récupérer l'utilisateur connecté ($user)
                    if ($searchDTO -> getRegistered ()) {
                        $queryBuilder -> leftJoin ( 's.participants', 'p' )
                            -> andWhere ( 'p.id = :userId' )
                            -> setParameter ( 'userId', $user -> getId () );
                    } elseif ($searchDTO -> getNotRegistered ()) {
                        $queryBuilder -> leftJoin ( 's.participants', 'p' )
                            -> andWhere ( 'p.id != :userId' )
                            -> setParameter ( 'userId', $user -> getId () );
                    }


                    if ($searchDTO -> getPastEvents ()) {
                        $queryBuilder -> andWhere ( 's.dateHeureDebut < :now' )
                            -> setParameter ( 'now', new \DateTime() );
                    }


                    // ...

                    // Exécuter la requête et récupérer les résultats
                    $resultats = $queryBuilder -> getQuery () -> getResult ();
                    //dd($resultats);

                }

                // Rendre la vue avec les données nécessaires
                return $this -> render ( 'home/index.html.twig', [
                    'user' => $user,
                    'isAdmin' => $isAdmin,
                    'searchForm' => $searchForm -> createView (),
                    'resultats' => $resultats ]);



    }

    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    // ...

    private function getDoctrine(): ManagerRegistry
    {
        return $this->doctrine;
    }


}

