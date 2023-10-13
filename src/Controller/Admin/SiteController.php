<?php

namespace App\Controller\Admin;

use App\Entity\Sites;
use App\Form\SiteType;
use App\Repository\SitesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/site', name: 'app_site')]
class SiteController extends AbstractController
{
    #[Route('/', name: '_liste')]
    public function index(Request $request,
                          EntityManagerInterface $entityManager,
                          SitesRepository $sitesRepository
                          ): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $site = new Sites();

        $form = $this->createForm(SiteType::class, $site);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($site);
            $entityManager->flush();
        }

        $sites = $sitesRepository->findBy([],['nom' => 'ASC']);
        //$sites = $sitesRepository->findAll();

        return $this->render('admin/site/index.html.twig', [
            'sites' => $sites,
            'form' => $form,
        ]);
    }

    #[Route('/supprimer/{id}', name: '_supprimer')]
    public function suprimer(EntityManagerInterface $entityManager,
                             SitesRepository $sitesRepository,
                             int $id):Response {

        //S'il existe, on est dans le cas de la modification
        $site = $sitesRepository->find($id);

        //traitement des données
        $entityManager->remove($site); //sauvegarde le bien
        $entityManager->flush(); //enregistre en base

        $this->addFlash(
            'success',
            'le site à bien été suprimé.');

        return  $this->redirectToRoute('app_site_liste');
    }
}
