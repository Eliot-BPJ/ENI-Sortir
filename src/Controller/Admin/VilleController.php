<?php

namespace App\Controller\Admin;

use App\Entity\Villes;
use App\Form\VilleType;
use App\Repository\VillesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ville', name: 'app_admin_ville')]
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
    #[Route('/modifier/{id}', name: '_update', methods: ['POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager, $id = null): JsonResponse
    {
        $ville = $entityManager->getRepository(Villes::class)->find($id);

        if (!$ville) {
            return new JsonResponse(['message' => 'Ville introuvable'], Response::HTTP_NOT_FOUND);
        }
        try {
            $editedNom = $request->request->get('editedNom');
            $editedCodePostal = $request->request->get('editedCodePostal');

            $ville->setNom($editedNom);
            $ville->setCodePostal($editedCodePostal);

            $entityManager->persist($ville);
            $entityManager->flush();

            return new JsonResponse(['message' => 'Ville mise à jour avec succès']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
