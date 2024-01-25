<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile', name: 'profile_')]
class PorfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {

        return $this->render('porfile/index.html.twig', [
            'controller_name' => 'PorfileController',
        ]);
    }
}
