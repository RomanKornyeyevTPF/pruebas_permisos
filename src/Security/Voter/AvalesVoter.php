<?php

namespace App\Security\Voter;

use App\Entity\Aval;
use App\Entity\Usuario;
use App\Repository\RolRecursoPermisoRepository;
use App\Repository\PermisoDirectoRecursoRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AvalesVoter extends Voter
{
    private RolRecursoPermisoRepository $rolPermisoRepo;
    private PermisoDirectoRecursoRepository $permisoDirectoRepo;

    private const SUPPORTED_ACTIONS = [
        'CERRAR_AVAL' => 'CERRAR',
        'ABRIR_AVAL' => 'ABRIR',
        'CANCELAR_AVAL' => 'CANCELAR',
    ];

    public function __construct(
        RolRecursoPermisoRepository $rolPermisoRepo,
        PermisoDirectoRecursoRepository $permisoDirectoRepo
    ) {
        $this->rolPermisoRepo = $rolPermisoRepo;
        $this->permisoDirectoRepo = $permisoDirectoRepo;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return isset(self::SUPPORTED_ACTIONS[$attribute]) && $subject instanceof Aval;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
      $usuario = $token->getUser();
      if (!$usuario instanceof Usuario) {
          return false;
      }

      $aval = $subject;
      $accion = self::SUPPORTED_ACTIONS[$attribute];
      $recurso = $aval->getRecurso();
      $ambito = $aval->getAmbito();

      // echo "Acción: $accion, Recurso: $recurso, Ámbito: $ambito<br>";
      // die;

      $permitir = false;

      /**
       * SI QUEREMOS PRIORIZAR PERMITIR SOBRE DENEGAR, PONER RETURN EN TRUE (en vez de $perimitido = true)
       * SI QUEREMOS PRIORIZAR DENEGAR SOBRE PERMITIR, PONER RETURN EN FALSE
       */
      // foreach ($usuario->getRolesBbdd() as $rol) {
      //   $permisos = $this->rolPermisoRepo->buscarPermisosDeRol($rol, $accion, $recurso, $ambito);
      //   foreach ($permisos as $permiso) {
      //     if ($permiso->getEfecto() === 'PERMITIR') { // efecto en la operación permitir
      //       return true;
      //     }
      //     if ($permiso->getEfecto() === 'DENEGAR') {
      //       $permitido = false;
      //     }
      //   }
      // }

      // Revisión por permisos directos
      // $efecto = $this->permisoDirectoRepo->usuarioTienePermisoDirecto($usuario, $accion, $recurso, $ambito);
      // if ($efecto === 'DENEGAR') return false;
      // if ($efecto === 'PERMITIR') return true;



      $efectos = [];

      // Permisos directos
      $efecto = $this->permisoDirectoRepo->usuarioTienePermisoDirecto($usuario, $accion, $recurso, $ambito);
      if ($efecto === 'DENEGAR') return false;
      if ($efecto === 'PERMITIR') return true;

      // Aplica la lógica de colisión (preferencia para denegar)
      if (in_array('DENEGAR', $efectos)) return false;
      if (in_array('PERMITIR', $efectos)) return true;

      // Permisos por rol
      foreach ($usuario->getRolesBbdd() as $rol) {
        $permisos = $this->rolPermisoRepo->buscarPermisosDeRol($rol, $accion, $recurso, $ambito);

        foreach ($permisos as $permiso) {
          $efectos[] = $permiso->getEfecto();
        }
      }

      // Aplica la lógica de colisión (preferencia para denegar)
      if (in_array('DENEGAR', $efectos)) return false;
      if (in_array('PERMITIR', $efectos)) return true;
      
      return false;
    }
}
