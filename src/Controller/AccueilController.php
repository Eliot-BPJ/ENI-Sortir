<?php

namespace App\Controller;

use App\DTO\FiltersDTO;
use App\Form\FiltersFormType;
use App\Repository\SortieRepository;
use PhpParser\Node\Expr\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil/liste', name: 'app_accueil')]
    public function index(SortieRepository $sortieRepository, Request $request): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $sorties = $sortieRepository->findAll();

        $filters = new FiltersDTO();
        $form = $this->createForm(FiltersFormType::class, $filters);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $filters = $form->getData();
            $sorties = $sortieRepository->findWithSearchFilters($filters, $this->getUser());
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

        return $this->render('accueil/index.html.twig',
            ['sorties' => $sorties,
             'form' => $form,
             'inscrit' => $inscrit]);
    }
}
