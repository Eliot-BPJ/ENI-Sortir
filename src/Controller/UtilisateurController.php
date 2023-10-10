<?php

namespace App\Controller;

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

    #[Route('/modifier/{id}', name: '_modifier')]
    public function editer(Request $request,
                           EntityManagerInterface $entityManager,
                           UtilisateurRepository $utilisateurRepository,
                           UploadService $uploadService,
                           int $id = null): Response
    {
        //TODO valeur a enlevé une fois les sessions faites !!!
        $id=1;
        $utilisateur = $utilisateurRepository->find($id);

        // je contrôle que l'utilisateur correspond bien à la personne connecté
        if($utilisateur->getUserIdentifier() !== $this->getUser()){

            $this->addFlash(
                'danger',
                'Non petit coquin, je sais où tu habites'
            );

            //return $this->redirectToRoute('app_admin_bien_lister');
        }

        $form = $this->createForm(UtilisateurType::class,$utilisateur);

        $form->handleRequest($request);

        // si le form est soumis et est valide
        if($form->isSubmitted() && $form->isValid()){

            // JE GERE L'UPLOAD ICI
            if ($form->get('imageProfil')->getData()) {
                $newFilename = $uploadService->upload($form->get('imageProfil')->getData(), $this->getParameter('imageProfil_directory'));
                $utilisateur->setImageProfil($newFilename);
            }
            if($utilisateur->getPassword() == $form->get('password')->getData()){
                $entityManager->persist($utilisateur);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'L \'utilisateur a été modifié !'
                );
            }
            // traitement des données


            //return $this->redirectToRoute('app_utilisateur_ajouter');

        }

        return $this->render('utilisateur/index.html.twig',[
            'form' => $form,
            'utilisateur' => $utilisateur
        ]);
    }
}
