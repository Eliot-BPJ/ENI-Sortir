<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UpdatePasswordType;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/utilisateur', name: 'app_utilisateur')]
class UtilisateurController extends AbstractController
{
    #[Route('/modifier', name: '_modifier')]
    public function editer(Request $request,
                           EntityManagerInterface $entityManager,
                           UploadService $uploadService): Response
    {
        // Récupérez l'utilisateur connecté
        $utilisateur = $this->getUser();

        // Créez le formulaire de modification
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $passwordField = $form->get('password');
            $currentPassword = $passwordField->getData();

            if (empty($currentPassword)) {
                // L'utilisateur n'a pas fourni de mot de passe actuel
                $this->addFlash('error',
                             'Mot de passe actuel requis.');
            } elseif (password_verify($currentPassword, $utilisateur->getPassword())) {
                // Mot de passe actuel correct, vous pouvez continuer avec les modifications.
                // JE GERE L'UPLOAD ICI
                if ($form->get('imageProfil')->getData()) {
                    $newFilename = $uploadService->upload($form->get('imageProfil')->getData(), $this->getParameter('imageProfil_directory'));
                    $utilisateur->setImageProfil($newFilename);
                }

                // Enregistrez les modifications dans la base de données
                $entityManager->persist($utilisateur);
                $entityManager->flush();

                $this->addFlash(
                        'success',
                     'L \'utilisateur a été modifié !'
                    );

                // Redirigez l'utilisateur vers une autre page (par exemple, page_bateau.html.twig)
                return $this->redirectToRoute('app_page_bateau');
            }
        } else {
            // Mot de passe actuel incorrect
            $this->addFlash('error',
                         'Mot de passe incorrect.');
        }

        return $this->render('utilisateur/index.html.twig', [
            'form' => $form->createView(),
            'utilisateur' => $utilisateur,
        ]);
    }

    #[Route('/modifier/pw', name: '_modifier_pw')]
    public function updatePassword(Request $request,
                                   EntityManagerInterface $entityManager,
                                   UserPasswordHasherInterface $userPasswordHasher): Response
    {
        // Récupérez l'utilisateur connecté
        $utilisateur = $this->getUser();

        // Récupérez le mot de passe stocké en base de données
        //pour le debug
        $storedPassword = $utilisateur->getPassword();

        // Créez le formulaire de modification
        $form = $this->createForm(UpdatePasswordType::class, $utilisateur);
        $form->handleRequest($request);

        // Récupérez le mot de passe actuel saisi dans le formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // Récuperez le mot de passe saisie
            $currentPassword = $form->get('currentPassword')->getData();

            // Ajoutez des instructions de débogage pour comparer les mots de passe
            if ($userPasswordHasher->isPasswordValid($utilisateur, $currentPassword)) {
                $newPassword = $form->get('password')->get('first')->getData();
                // Hash du nouveau mot de passe
                $hashedPassword = $userPasswordHasher->hashPassword($utilisateur, $newPassword);
                $utilisateur->setPassword($hashedPassword);

                // persistance des données
                $entityManager->persist($utilisateur);
                $entityManager->flush();

                $this->addFlash('success', 'L\'utilisateur a été modifié !');

                // Redirigez l'utilisateur vers une autre page (par exemple, page_bateau.html.twig)
                return $this->redirectToRoute('app_page_bateau');
            } else {
                // Mot de passe actuel incorrect
                $this->addFlash('error', 'Mot de passe actuel incorrect.');
            }
        }

        return $this->render('utilisateur/update_password.html.twig', [
            'form' => $form->createView(),
            'utilisateur' => $utilisateur,
        ]);
    }
    #[Route('/participant/{id}', name: '_afficher_participant')]
    public function afficherUtilisateur(UtilisateurRepository $utilisateurRepository,
                             int $id): Response {

        $utilisateur = $utilisateurRepository->find($id);


        return $this->render('utilisateur/afficherUtilisateur.html.twig',
            [
                'utilisateur' => $utilisateur,
            ]);
    }
}
