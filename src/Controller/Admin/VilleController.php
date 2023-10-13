<?php

namespace App\Controller\Admin;

use App\Entity\Villes;
use App\Form\VilleType;
use App\Repository\VillesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ville', name: 'app_ville')]
class VilleController extends AbstractController
{
    #[Route('/', name: '_liste')]
    public function index(Request $request,
                          EntityManagerInterface $entityManager,
                          VillesRepository $villesRepository
                          ): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $ville = new Villes();

        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();
        }

        $villes = $villesRepository->findBy([],['nom' => 'ASC']);

        return $this->render('admin/ville/index.html.twig', [
            'villes' => $villes,
            'form' => $form,
        ]);
    }

    #[Route('/supprimer/{id}', name: '_supprimer')]
    public function suprimer(EntityManagerInterface $entityManager,
                             VillesRepository $villesRepository,
                             int $id):Response {

        //S'il existe, on est dans le cas de la modification
        $ville = $villesRepository->find($id);

        //traitement des données
        $entityManager->remove($ville); //sauvegarde le bien
        $entityManager->flush(); //enregistre en base

        $this->addFlash(
            'success',
            'la ville a bien été suprimé.');

        return  $this->redirectToRoute('app_ville_liste');
    }

}
