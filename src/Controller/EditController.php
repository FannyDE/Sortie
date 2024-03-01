<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditFormType;
use App\Form\EditPasswordFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile instanceof UploadedFile) {
                $newFilename = uniqid() . '.' . $profilePictureFile->guessExtension();
                try {
                    $profilePictureFile->move(
                        $this->getParameter('profile_picture_directory'),
                        $newFilename
                    );

                    // Mettez à jour le chemin de la photo de profil dans l'entité User
                    $user->setProfilePicture($newFilename);
                } catch (FileException $e) {
                    // Gérer les erreurs de téléchargement ici, si nécessaire
                }
            } else {
                // Si aucun nouveau fichier n'a été téléchargé, conservez le nom de fichier actuel
                $newFilename = $user->getProfilePicture();
            }

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
            'user' => $this->getUser()
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
