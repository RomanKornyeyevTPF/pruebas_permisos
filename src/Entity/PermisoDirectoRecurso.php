<?php

namespace App\Entity;

use App\Repository\PermisoDirectoRecursoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermisoDirectoRecursoRepository::class)]
#[ORM\Table(name: 'permisos_directos_recursos')]
class PermisoDirectoRecurso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class, inversedBy: 'permisosDirectos')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Usuario $usuario = null;

    #[ORM\ManyToOne(targetEntity: Grupo::class, inversedBy: 'permisosDirectos')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?Grupo $grupo = null;

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

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): self
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getGrupo(): ?Grupo
    {
        return $this->grupo;
    }

    public function setGrupo(?Grupo $grupo): self
    {
        $this->grupo = $grupo;
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
