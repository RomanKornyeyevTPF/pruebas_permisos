<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mis-permisos')]
class UsuarioPermisosController extends AbstractController
{
    #[Route('', name: 'usuario_permisos')]
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        /** @var Usuario $usuario */
        $usuario = $this->getUser();

        $roles = $usuario->getRoles(); // strings tipo ROLE_AVALES
        $permisosPorRol = $usuarioRepository->getPermisosPorRol($usuario);
        $permisosDirectos = $usuarioRepository->getPermisosDirectos($usuario);

        return $this->render('usuario_permisos/index.html.twig', [
            'usuario' => $usuario,
            'roles' => $roles,
            'permisosPorRol' => $permisosPorRol,
            'permisosDirectos' => $permisosDirectos,
        ]);
    }
}
