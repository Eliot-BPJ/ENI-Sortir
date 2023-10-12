<?php

namespace App\Controller\Admin;

use App\DTO\FiltersDTO;
use App\Form\FiltersFormType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/sortie', name: 'app_admin_sortie')]
class SortieController extends AbstractController
{
    #[Route('/', name: '_lister')]
    public function index(Request $request, SortieRepository $sortieRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $filters = new FiltersDTO();
        $form = $this->createForm(FiltersFormType::class, $filters);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filters = $form->getData();
            $sorties = $sortieRepository->findWithSearchFilters($filters);
        } else {
            $sorties = $sortieRepository->findAll();
        }

        $inscrit = [];
        for ($i = 0; $i < count($sorties); $i++) {
            $inscrit[$i] = 0;
            $inscriptions = $sorties[$i]->getInscriptions();
            foreach ($inscriptions as $user) {
                if ($this->getUser() && $user->getId() === $this->getUser()->getUserIdentifier()) {
                    $inscrit[$i] = 1;
                    continue 2;
                }
            }
        }
        $sorties = array_filter($sorties, function($sorties) {
            return !$sorties->isEstHistorise();
        });

        return $this->render('admin/sortie/index.html.twig',
            ['sorties' => $sorties,
                'form' => $form,
                'inscrit' => $inscrit]);
    }
    #[Route('/annuler/{id}', name: '_annuler')]
    public function annulerSortie(EntityManagerInterface $entityManager,
                                  SortieRepository $sortieRepository,
                                  int $id): Response {
        $sortie = $sortieRepository->find($id);
        $sortie->setEstHistorise(true);
        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_sortie_lister');
    }
}
