<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class FrontpageController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $user = $this->getUser();

        return $this->render('home.html.twig', ['user' => $user]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
