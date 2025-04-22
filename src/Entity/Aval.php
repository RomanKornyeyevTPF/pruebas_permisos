<?php

namespace App\Entity;

use App\Repository\AvalRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvalRepository::class)]
#[ORM\Table(name: 'avales')]
class Aval
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 50)]
    private string $estado = 'abierto';

    #[ORM\Column(type: 'string', length: 10)]
    private string $pais = 'ES';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;
        return $this;
    }

    public function getPais(): string
    {
        return $this->pais;
    }

    public function setPais(string $pais): self
    {
        $this->pais = $pais;
        return $this;
    }

    public function estaAbierto(): bool
    {
        return strtolower($this->estado) === 'abierto';
    }

    public function getAmbito(): string
    {
        if ($this->pais === 'ES') {
            return 'NACIONAL';
        }
    
        return 'INTERNACIONAL';
    }
    

    public function getRecurso(): string
    {
        return $this->estaAbierto() ? 'Cerrar Aval' : 'Abrir Aval';
    }
}
