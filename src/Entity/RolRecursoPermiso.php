<?php

namespace App\Entity;

use App\Repository\RolRecursoPermisoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RolRecursoPermisoRepository::class)]
#[ORM\Table(name: 'roles_recursos_permisos')]
class RolRecursoPermiso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Role::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Role $rol;

    #[ORM\ManyToOne(targetEntity: Recurso::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Recurso $recurso;

    #[ORM\ManyToOne(targetEntity: Accion::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private Accion $accion;

    #[ORM\ManyToOne(targetEntity: AmbitoDato::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private AmbitoDato $ambito;

    #[ORM\Column(type: 'string', length: 10)]
    private string $efecto; // 'PERMITIR' o 'DENEGAR'

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRol(): Role
    {
        return $this->rol;
    }

    public function setRol(Role $rol): self
    {
        $this->rol = $rol;
        return $this;
    }

    public function getRecurso(): Recurso
    {
        return $this->recurso;
    }

    public function setRecurso(Recurso $recurso): self
    {
        $this->recurso = $recurso;
        return $this;
    }

    public function getAccion(): Accion
    {
        return $this->accion;
    }

    public function setAccion(Accion $accion): self
    {
        $this->accion = $accion;
        return $this;
    }

    public function getAmbito(): AmbitoDato
    {
        return $this->ambito;
    }

    public function setAmbito(AmbitoDato $ambito): self
    {
        $this->ambito = $ambito;
        return $this;
    }

    public function getEfecto(): string
    {
        return $this->efecto;
    }

    public function setEfecto(string $efecto): self
    {
        $this->efecto = $efecto;
        return $this;
    }
}
