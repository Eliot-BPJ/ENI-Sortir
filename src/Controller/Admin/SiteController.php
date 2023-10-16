<?php

namespace App\Controller\Admin;

use App\Entity\Sites;
use App\Form\SiteType;
use App\Repository\SitesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[Route('/modifier/{id}', name: '_update')]
    public function update(Request $request,
                           EntityManagerInterface $entityManager,
                           $id = null): Response
    {
        $site = $entityManager->getRepository(Sites::class)->find($id);

        if (!$site) {
            // Gérer le cas où le site n'est pas trouvé
            return new JsonResponse(['message' => 'Site introuvable'], Response::HTTP_NOT_FOUND);
        }

        $newSiteName = $request->request->get('editedSite');

        // Mettre à jour le nom du site
        $site->setNom($newSiteName);

        $entityManager->persist($site);
        $entityManager->flush();

        // Répondre avec un message de succès
        return new JsonResponse(['message' => 'Site mis à jour avec succès']);
    }
}
