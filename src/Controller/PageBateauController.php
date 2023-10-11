<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageBateauController extends AbstractController
{
    #[Route('/page/bateau', name: 'app_page_bateau')]
    public function index(): Response
    {
        return $this->render('page_bateau/index.html.twig', [
            'controller_name' => 'PageBateauController',
        ]);
    }
}
