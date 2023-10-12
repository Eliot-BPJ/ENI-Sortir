<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use App\Entity\Sites;
use App\Form\RegisterWithCsvType;
use App\Repository\SitesRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Exception;
use League\Csv\UnavailableStream;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function supprimerRealisation(EntityManagerInterface $entityManager,
                                         UtilisateurRepository $utilisateurRepository,
                                         int $id): Response {
        $utilisateur = $utilisateurRepository->find($id);
        //$utilisateur->setPassword('historiser');
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
    public function importUsers(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SitesRepository $sitesRepository): Response
    {
        $form = $this->createForm(RegisterWithCsvType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $csvFile = $form->get('fichierCSV')->getData();

            $csvFilePath = $csvFile->getPathname();

            $csv = Reader::createFromPath($csvFilePath, 'r');
            $csv->setHeaderOffset(0);

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
                $entityManager->persist($utilisateur);

            }
            $entityManager->flush();
            return $this->redirectToRoute('app_admin_utilisateur_lister');
        }

        return $this->render('admin/utilisateur/adminAjoutRegisterWithCsv.html.twig', [
            'formRegisterWithCsv' => $form->createView(),
        ]);
    }
}
