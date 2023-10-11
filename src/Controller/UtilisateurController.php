<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

            // JE GERE L'UPLOAD ICI
            if ($form->get('imageProfil')->getData()) {
                $newFilename = $uploadService->upload($form->get('imageProfil')->getData(), $this->getParameter('photo_profil'));
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

        return $this->render('utilisateur/index.html.twig', [
            'form' => $form->createView(),
            'utilisateur' => $utilisateur,
        ]);
    }
}