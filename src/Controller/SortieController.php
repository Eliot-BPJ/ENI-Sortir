<?php

namespace App\Controller;

use App\Entity\Etats;
use App\Entity\Sites;
use App\Entity\Sortie;
use App\Form\AnnulerSortieType;
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
    #[Route('/voir/{id}', name: '_list')]
    public function read(
        Request $request,
        EntityManagerInterface $entityManager,
        SortieRepository $sortieRepository,
        SluggerInterface $slugger,
        int $id = null
    ): Response {
        $sortie = $sortieRepository->find($id);

        return $this->render('sortie/voir.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/add', name: '_add')]
    #[Route('/save', name: '_save')]
    #[Route('/edit/{id}', name: '_edit')]
    public function editer(
        Request $request,
        EntityManagerInterface $entityManager,
        SortieRepository $sortieRepository,
        SluggerInterface $slugger,
        int $id = null
    ): Response {
        if ($id == null) {
            $sortie = new Sortie();
        } else {
            $sortie = $sortieRepository->find($id);
        }

        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $sortie->setSite($this->getUser()->getIdSite());
            $sortie->setOrganisateur($this->getUser());

            $dateDeb = $form->get("dateDebut")->getData();
            $dateFin = $form->get("dateRetour")->getData();
            $diff_in_seconds = $dateFin->getTimestamp() - $dateDeb->getTimestamp();
            $duree =  floor($diff_in_seconds / 60); #in minutes

            $sortie->setDuree($duree);

            if($request->request->has('save')) {
                $etat = Etats::Creee;
            } else if($request->request->has('add')) {
                $etat = Etats::Ouverte;
            }

            $sortie->setEtat($etat);
            $sortie->setEstHistorise(false);

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('sortie/editer.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/voir/{id}/inscription', name: '_signup')]
    public function signup(
        Request $request,
        EntityManagerInterface $entityManager,
        SortieRepository $sortieRepository,
        SluggerInterface $slugger,
        int $id = null
    ): Response
    {
        $sortie = $sortieRepository->find($id);

        $signed_up_ids = [];
        foreach ($sortie->getInscriptions()->getValues() as $inscrit) {
            array_push($signed_up_ids, $inscrit->getId());
        };

        if (!in_array($this->getUser()->getId(), $signed_up_ids)) {
            $sortie->addInscription($this->getUser());

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('sortie/voir.html.twig', [
            'sortie' => $sortie,
        ]);
    }

    #[Route('/annuler/{id}', name: '_annuler')]
    public function annulerSortie(Request $request,
                                  EntityManagerInterface $entityManager,
                                  SortieRepository $sortieRepository,
                                  int $id): Response {
        $sortie = $sortieRepository->find($id);
        if($sortie->getEtat()->value === "Ouverte") {
            $form = $this->createForm(AnnulerSortieType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $sortie->setMotifAnnulation($form->get('motifAnnulation')->getData());
                $sortie->setEstHistorise(true);
                $entityManager->persist($sortie);
                $entityManager->flush();
                return $this->redirectToRoute('app_accueil');
            }
            return $this->render('sortie/annulerSortie.html.twig', [
                'formAnnulationSortie' => $form->createView(),
            ]);
        } else {
            $sortie->setEstHistorise(true);
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('app_accueil');
        }
    }
}
