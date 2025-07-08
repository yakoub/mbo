<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\LoginType;

class AuthenticationController extends AbstractController
{
    public function login(Request $request, AuthenticationUtils $util): Response
    {
        $error = $util->getLastAuthenticationError();
        $username = $util->getLastUsername();

        return $this->render('authentication.html.twig', [
            'error' => $error,
            'username' => $username,
        ]);
    }
}
