<?php

namespace App\Entity;

use App\Repository\RecursoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecursoRepository::class)]
#[ORM\Table(name: 'recursos')]
class Recurso
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 100)]
    private string $nombre;

    #[ORM\Column(type: 'string', length: 50)]
    private string $tipoRecurso; // ejemplo: MODULO, SUBMODULO, FUNCIONALIDAD

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'hijos')]
    #[ORM\JoinColumn(name: 'recurso_padre_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Recurso $recursoPadre = null;

    #[ORM\OneToMany(mappedBy: 'recursoPadre', targetEntity: self::class)]
    private Collection $hijos;

    public function __construct()
    {
        $this->hijos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getTipoRecurso(): string
    {
        return $this->tipoRecurso;
    }

    public function setTipoRecurso(string $tipoRecurso): self
    {
        $this->tipoRecurso = $tipoRecurso;
        return $this;
    }

    public function getRecursoPadre(): ?self
    {
        return $this->recursoPadre;
    }

    public function setRecursoPadre(?self $recursoPadre): self
    {
        $this->recursoPadre = $recursoPadre;
        return $this;
    }

    /**
     * @return Collection<int, Recurso>
     */
    public function getHijos(): Collection
    {
        return $this->hijos;
    }

    public function addHijo(Recurso $hijo): self
    {
        if (!$this->hijos->contains($hijo)) {
            $this->hijos[] = $hijo;
            $hijo->setRecursoPadre($this);
        }
        return $this;
    }

    public function removeHijo(Recurso $hijo): self
    {
        if ($this->hijos->removeElement($hijo)) {
            if ($hijo->getRecursoPadre() === $this) {
                $hijo->setRecursoPadre(null);
            }
        }
        return $this;
    }
}
