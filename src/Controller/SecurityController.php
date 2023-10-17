<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, UtilisateurRepository $utilisateurRepository): Response
    {
         if ($this->getUser() ) {
             return $this->redirectToRoute('app_accueil');

         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): never
    {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    #[Route(path: '/mot-de-passe-oublie', name: 'app_forgot_password')]
    #[Route(path: '/mot-de-passe-oublie/{email}', name: 'app_forgot_password_email')]
    public function forgotPassword(String $email, UtilisateurRepository $utilisateurRepository ): Response
    {
        if($email) {
            $utilisateur = $utilisateurRepository->findOneBy(['email' => $email]);
        }

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
}
