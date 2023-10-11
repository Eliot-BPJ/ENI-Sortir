<?php

namespace App\Controller;

use App\Entity\Sites;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

#[Route('/sortie', name: 'app_sortie')]
class SortieController extends AbstractController
{
    #[Route('/add', name: '_add')]
    #[Route('/edit/{id}', name: '_edit')]
    public function editer(
        Request $request,
        EntityManagerInterface $entityManager,
        SortieRepository $sortieRepository,
        SluggerInterface $slugger,
        int $id = null
    ): Response
    {
        if ($id == null) {
            $sortie = new Sortie();
        } else {
            $sortie = $sortieRepository->find($id);
        }

        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $site = new Sites();
            $site->setNom("Niort");
            $sortie->setSite($site);

            $duree = date_diff($form->get("dateDebut")->getData(), $form->get("dateDebut")->getData());

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('app_sortie_edit');
        }

        return $this->render('sortie/editer.html.twig', [
            'form' => $form,
        ]);
    }
}
