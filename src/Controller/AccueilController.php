<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil/liste', name: 'app_accueil')]
    public function index(SortieRepository $sortieRepository): Response
    {
        $user = $this->getUser();
        if ($user) {
            $sorties = $sortieRepository->findAll();
        }else{
            return $this->redirectToRoute('app_login');
        }
        return $this->render('accueil/index.html.twig',
            ['sorties' => $sorties]);
    }
}
