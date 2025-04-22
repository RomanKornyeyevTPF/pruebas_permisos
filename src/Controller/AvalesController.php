<?php

namespace App\Controller;

use App\Entity\Aval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/avales')]
class AvalesController extends AbstractController
{
    #[Route('', name: 'avales_index')]
    #[IsGranted('ROLE_AVALES')]
    public function index(): Response
    {
        return new Response('Listado de avales');
    }

    #[Route('/{id}', name: 'avales_ver')]
    public function ver(int $id, EntityManagerInterface $em): Response
    {
        $aval = $em->getRepository(Aval::class)->find($id);
        if (!$aval) {
            throw $this->createNotFoundException();
        }

        return new Response("Aval #{$aval->getId()}, estado: {$aval->getEstado()}");
    }

    #[Route('/cerrar/{id}', name: 'avales_cerrar')]
    public function cerrar(int $id, EntityManagerInterface $em): Response
    {
        $aval = $em->getRepository(Aval::class)->find($id);
        $this->denyAccessUnlessGranted('CERRAR_AVAL', $aval);

        $aval->setEstado('cerrado');
        $em->flush();

        return new Response("Aval #{$aval->getId()} cerrado");
    }

    #[Route('/abrir/{id}', name: 'avales_abrir')]
    public function abrir(int $id, EntityManagerInterface $em): Response
    {
        $aval = $em->getRepository(Aval::class)->find($id);
        $this->denyAccessUnlessGranted('ABRIR_AVAL', $aval);

        $aval->setEstado('abierto');
        $em->flush();

        return new Response("Aval #{$aval->getId()} abierto");
    }

    #[Route('/cancelar/{id}', name: 'avales_cancelar')]
    public function cancelar(int $id, EntityManagerInterface $em): Response
    {
        $aval = $em->getRepository(Aval::class)->find($id);
        $this->denyAccessUnlessGranted('CANCELAR_AVAL', $aval);

        $aval->setEstado('cancelado');
        $em->flush();

        return new Response("Aval #{$aval->getId()} cancelado");
    }
}
