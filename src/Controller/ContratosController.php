<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContratosController extends AbstractController
{
    #[Route('/contratos', name: 'app_contratos')]
    public function index(): Response
    {
        return $this->render('contratos/index.html.twig', [
            'controller_name' => 'ContratosController',
        ]);
    }
}
