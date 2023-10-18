<?php

namespace App\Controller;

use App\DTO\FiltersDTO;
use App\Entity\Etats;
use App\Entity\Lieu;
use App\Entity\Sites;
use App\Entity\Sortie;
use App\Form\AnnulerSortieType;
use App\Form\FiltersFormType;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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

        $nbInscrit = 0;
        foreach ($sortie->getInscriptions()->getValues() as $inscrit) {
            $nbInscrit++;
        };

        $datetimeFin = new \DateTime($sortie->getDateDebut()->format('Y-m-d H:i'));
        date_add($datetimeFin, new \DateInterval('PT' . $sortie->getDuree() . 'M'));

        return $this->render('sortie/voir.html.twig', [
            'sortie' => $sortie,
            'nbInscrit' => $nbInscrit,
            'datetimeFin' => $datetimeFin,
            'datetimeActuelle' => new \DateTime('now')
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
        LieuRepository $lieuRepository,
        int $id = null
    ): Response {
        if ($id == null) {
            $sortie = new Sortie();
        } else {
            $sortie = $sortieRepository->find($id);
        }
        $lieu = new Lieu();
        $idLieu = -1;
        $form = $this->createForm(SortieType::class, $sortie);

        $formLieu = $this->createForm(LieuType::class,$lieu);

        $form->handleRequest($request);
        $formLieu->handleRequest($request);

        if ($lieu->getNom()) {

            $entityManager->persist($lieu);
            $entityManager->flush();

            $lieux = $lieuRepository->findAll();
            foreach ($lieux as $l){
                if($l->getId() > $idLieu) {
                    $idLieu = $l->getId();
                }
            }
        }
        if ($form->isSubmitted() && $form->isValid()) {

            $sortie->setSite($this->getUser()->getIdSite());
            $sortie->setOrganisateur($this->getUser());

            $dateInscription = $form->get("dateLimiteInscription")->getData();
            $nbInscription = $form->get("nbInscriptionMax")->getData();

            //conversion des dates en durée
            $dateDeb = $form->get("dateDebut")->getData();
            $dateFin = $form->get("dateRetour")->getData();
            $diff_in_seconds = $dateFin->getTimestamp() - $dateDeb->getTimestamp();
            $duree =  floor($diff_in_seconds / 60); #in minutes
            //si la date de debut de la sortie est avant la date de fin
            //si la date d'inscription est avant la date de début
            //je traite des données

            if($dateFin>$dateDeb && $dateDeb>$dateInscription && $nbInscription>1){
                $sortie->setDuree($duree);

                if($request->request->has('save')) {
                    $etat = Etats::Creee;
                } else if($request->request->has('add')) {
                    $etat = Etats::Ouverte;
                }

                $sortie->setEtat($etat);
                $sortie->setEstHistorise(false);
                if($idLieu !== -1) {
                    $lieu = $lieuRepository->findOneBy((array('id' => $idLieu)));
                    $sortie->setLieux($lieu);
                }
                $entityManager->persist($sortie);
                $entityManager->flush();

                return $this->redirectToRoute('app_accueil');
            } else {
                //affichage d'un message flash en fonction de chaque erreur
                if($dateFin<$dateDeb){
                    $this->addFlash('error',
                        'Le début de la sortie doit être avant la date de fin de sortie');
                }
                if($dateDeb<$dateInscription){
                    $this->addFlash('error',
                        'La date limite d\'inscription doit être avant le début de la sortie.');
                }
                if($nbInscription<2){
                    $this->addFlash('error',
                        'Il doit y avoir au moins 2 participants.');
                }
            }

        }

        return $this->render('sortie/editer.html.twig', [
            'form' => $form,
            'formLieu' => $formLieu
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
        $nbInscrit = 0;
        $signed_up_ids = [];
        foreach ($sortie->getInscriptions()->getValues() as $inscrit) {
            array_push($signed_up_ids, $inscrit->getId());
            $nbInscrit++;
        };
        if (!in_array($this->getUser()->getId(), $signed_up_ids) && $this->getUser()->isAdministrateur() == 0 && $nbInscrit < $sortie->getNbInscriptionMax()) {

            $sortie->addInscription($this->getUser());

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('app_accueil');
        }

        return $this->render('sortie/voir.html.twig', [
            'sortie' => $sortie,
            'nbInscrit' => $nbInscrit
        ]);
    }

    #[Route('/voir/{id}/quitter', name: '_leave')]
    public function leave(
        Request $request,
        EntityManagerInterface $entityManager,
        SortieRepository $sortieRepository,
        SluggerInterface $slugger,
        int $id = null
    ): Response
    {
        $sortie = $sortieRepository->find($id);
        $sortie->removeInscription($this->getUser());

        $entityManager->persist($sortie);
        $entityManager->flush();

        return $this->redirectToRoute('app_accueil');
    }

    #[Route('/annuler/{id}', name: '_annuler')]
    public function annulerSortie(Request $request,
                                  EntityManagerInterface $entityManager,
                                  SortieRepository $sortieRepository,
                                  int $id): Response {
        $sortie = $sortieRepository->find($id);
        if($this->getUser()->getId() === $sortie->getOrganisateur()->getId() || in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            if($sortie->getEtat()->value === "Ouverte") {
                $form = $this->createForm(AnnulerSortieType::class);
                $form->handleRequest($request);
                if ($form->isSubmitted() && $form->isValid()) {
                    $sortie->setMotifAnnulation($form->get('motifAnnulation')->getData());
                    $sortie->setEstHistorise(true);
                    $sortie->setEtat(Etats::Annulee);
                    $entityManager->persist($sortie);
                    $entityManager->flush();
                    return $this->redirectToRoute('app_accueil');
                }
                return $this->render('sortie/annulerSortie.html.twig', [
                    'formAnnulationSortie' => $form->createView(),
                ]);
            } else {
                $sortie->setEtat(Etats::Annulee);
                $sortie->setEstHistorise(true);
                $entityManager->persist($sortie);
                $entityManager->flush();

                return $this->redirectToRoute('app_accueil');
            }
        }
        return $this->redirectToRoute('app_accueil');
    }
}
