<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Form\LoginType;

class AuthenticationController extends Controller
{
    public function login(Request $request, AuthenticationUtils $util)
    {
        $error = $util->getLastAuthenticationError();
        dump($error);
        $username = $util->getLastUsername();
        dump($username);

        return $this->render('authentication.html.twig', [
            'error' => $error,
            'username' => $username,
        ]);
    }
}
