<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditFormType;
use App\Form\EditPasswordFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;


class EditController extends AbstractController
{
    #[Route("/editProfile", name: "edit")]
    public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->flush();
            // do anything else you need here, like send an email

            $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
            return $this->redirectToRoute('home');
        }


        return $this->render('registration/edit.html.twig', [
            'editForm' => $form->createView(),
        ]);
    }

    #[Route("/editPassword", name: "editPassword")]
    public function editPass(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(EditPasswordFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->flush();
            // do anything else you need here, like send an email

            $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
            return $this->redirectToRoute('home');
        }


        return $this->render('registration/editPW.html.twig', [
            'editPWForm' => $form->createView(),
        ]);
    }
}