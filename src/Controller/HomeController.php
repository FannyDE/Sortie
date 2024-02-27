<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\User;
use App\Form\SearchSortieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;


class HomeController extends AbstractController
{
    #[Route("/home", name: "home")]
    public function index(Request $request):Response

    {
        $user = $this->getUser();
        $searchForm = $this->createForm(SearchSortieType::class);
        $searchForm->handleRequest($request);

        $resultats = null;

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchFormData = $searchForm->getData();

            $repository = $this->getDoctrine()->getRepository(Sortie::class);
            $queryBuilder = $repository->createQueryBuilder('s');

            if ($searchFormData['search']) {
                $queryBuilder->andWhere('s.nom LIKE :search')
                    ->setParameter('search', '%' . $searchFormData['search'] . '%');
            }

            if ($searchFormData['campus_id']) {
                $queryBuilder->andWhere('s.campus = :campus')
                    ->setParameter('campus', $searchFormData['campus_id']);
            }

            if ($searchFormData['startDate']) {
                $queryBuilder->andWhere('s.dateHeureDebut >= :startDate')
                    ->setParameter('startDate', $searchFormData['startDate']);
            }

            if ($searchFormData['endDate']) {
                $queryBuilder->andWhere('s.dateFin <= :endDate')
                    ->setParameter('endDate', $searchFormData['endDate']);
            }

            $resultats = $queryBuilder->getQuery()->getResult();
        }

            return $this->render('home/index.html.twig', [
                'user' => $user,
                'searchForm' => $searchForm->createView(),
                'resultats' => $resultats
            ]);
    }



    public function search(): Response
    {

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
