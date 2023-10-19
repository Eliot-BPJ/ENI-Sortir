<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use App\Entity\Sites;
use App\Form\AdminUtilisateurType;
use App\Form\RegisterWithCsvType;
use App\Form\UtilisateurType;
use App\Repository\SitesRepository;
use App\Repository\UtilisateurRepository;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Exception;
use League\Csv\UnavailableStream;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use League\Csv\Reader;

#[Route('/admin/utilisateur', name: 'app_admin_utilisateur')]
class UtilisateurController extends AbstractController
{
    #[Route('/', name: '_lister')]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateurs = $utilisateurRepository->findAll();
        $utilisateurs = array_filter($utilisateurs, function($utilisateurs) {
            return !$utilisateurs->isHistoriser();
        });
        return $this->render('admin/utilisateur/index.html.twig', [
            'controller_name' => 'AdminFilRougeController',
            'utilisateurs' => $utilisateurs
        ]);
    }
    #[Route('/desactiver/{id}', name: '_desactiver')]
    public function desactiver(UtilisateurRepository $utilisateurRepository, int $id = null,EntityManagerInterface $entityManager,): Response {
        $utilisateur = $utilisateurRepository->find($id);
        $utilisateur->setActif(!$utilisateur->isActif());
        $entityManager->persist($utilisateur);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_utilisateur_lister');
    }
    #[Route('/supprimer/{id}', name: '_supprimer')]
    public function supprimerUtilisateur(EntityManagerInterface $entityManager,
                                         UtilisateurRepository $utilisateurRepository,
                                         int $id): Response {
        $utilisateur = $utilisateurRepository->find($id);
        $utilisateur->setHistoriser(true);
        $entityManager->persist($utilisateur);
        $entityManager->flush();

//        $this->addFlash(
//            'notice',
//            'L'utilisateur a été supprimer'
//        );
        return $this->redirectToRoute('app_admin_utilisateur_lister');
    }

    /**
     * @throws UnavailableStream
     * @throws Exception
     */
    #[Route('/ajouter-csv', name: '_ajouter_csv')]
    public function importUsers(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SitesRepository $sitesRepository, UtilisateurRepository $utilisateurRepository): Response
    {
        $form = $this->createForm(RegisterWithCsvType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $csvFile = $form->get('fichierCSV')->getData();

            $csvFilePath = $csvFile->getPathname();

            $csv = Reader::createFromPath($csvFilePath, 'r');
            $csv->setHeaderOffset(0);
            $utilisateursNonEnregistre = [];

            foreach ($csv as $user) {
                $utilisateur = new Utilisateur();
                $utilisateur->setPseudo($user['pseudo']);
                $utilisateur->setEmail($user['email']);
                $utilisateur->setNom($user['nom']);
                $utilisateur->setPrenom($user['prenom']);
                $site = $sitesRepository->find($user['idSite']);
                $utilisateur->setIdSite($site);
                $utilisateur->setAdministrateur($user['administrateur']);
                $utilisateur->setActif($user['actif']);
                $utilisateur->setTelephone($user['telephone']);
                $utilisateur->setPassword(
                    $userPasswordHasher->hashPassword(
                        $utilisateur,
                        $user['password']
                    )
                );
                $utilisateur->setHistoriser(false);
                if($utilisateurRepository->findOneBy(['pseudo'=> $utilisateur->getPseudo()]) || $utilisateurRepository->findOneBy(['email'=>$utilisateur->getEmail()])) {
                    array_push($utilisateursNonEnregistre, $utilisateur->getPseudo());
                    $error = 'L\'utilisateur ne peut pas être car le pseudo ou l\'email est en doublon les utilisateurs concérnés sont :';
                } else {
                    $entityManager->persist($utilisateur);
                }
            }
            $entityManager->flush();
            if(count($utilisateursNonEnregistre) > 0) {
                foreach ($utilisateursNonEnregistre as $utilisateurNonEnregisre) {
                    $error = $error . ' ' . $utilisateurNonEnregisre;
                }
                    $this->addFlash('error',$error);
            }
            return $this->redirectToRoute('app_admin_utilisateur_lister');
        }

        return $this->render('admin/utilisateur/adminAjoutRegisterWithCsv.html.twig', [
            'formRegisterWithCsv' => $form->createView(),
        ]);
    }
    #[Route('/modifier/{id}', name: '_modifier')]
    public function editer(Request                $request,
                           EntityManagerInterface $entityManager,
                           UtilisateurRepository $utilisateurRepository,
                           UploadService          $uploadService,
                           int $id): Response
    {
        // Récupérez l'utilisateur connecté
        $utilisateur = $utilisateurRepository->find($id);
        $pseudo = $this->getUser()->getPseudo();

        // Créez le formulaire de modification
        $form = $this->createForm(AdminUtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            // Vérifiez si le pseudo a été modifié
            $newPseudo = $form->get('pseudo')->getData();

            //dd($newPseudo !== $pseudo);
            if ($newPseudo !== $pseudo) {
                // Le pseudo a été modifié, vérifiez s'il est unique
                $existingUser = $utilisateurRepository->findOneBy(['pseudo' => $newPseudo]);
                if ($existingUser && $existingUser !== $utilisateur) {
                    $form->get('pseudo')->addError(new FormError('Ce pseudo existe déjà.'));
                    $this->addFlash('error',
                        'Le pseudo existe déjà');
                }
            }
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
            return $this->redirectToRoute('app_admin_utilisateur_lister');
        }
        return $this->render('utilisateur/index.html.twig', [
            'form' => $form->createView(),
            'utilisateur' => $utilisateur,
        ]);
    }
}
