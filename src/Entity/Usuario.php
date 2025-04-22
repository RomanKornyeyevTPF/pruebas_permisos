<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ORM\Table(name: 'usuarios')]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private string $email;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string', length: 100)]
    private string $nombre;

    #[ORM\Column(type: 'boolean')]
    private bool $activo = true;

    #[ORM\ManyToMany(targetEntity: Role::class, inversedBy: 'usuarios')]
    #[ORM\JoinTable(name: 'usuarios_roles')]
    private Collection $rolesBbdd;

    #[ORM\ManyToMany(targetEntity: Grupo::class, mappedBy: 'usuarios')]
    private Collection $grupos;

    #[ORM\OneToMany(mappedBy: 'usuario', targetEntity: PermisoDirectoRecurso::class)]
    private Collection $permisosDirectos;

    public function __construct()
    {
        $this->rolesBbdd = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
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

    public function isActivo(): bool
    {
        return $this->activo;
    }

    public function setActivo(bool $activo): self
    {
        $this->activo = $activo;
        return $this;
    }

    /**
     * Symfony Security: identificador del usuario (en lugar de username).
     */
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * Devuelve los roles como array de strings para Symfony.
     */
    public function getRoles(): array
    {
        $roles = [];

        foreach ($this->rolesBbdd as $rol) {
            $roles[] = $rol->getNombreRol(); // Devuelve strings tipo "ROLE_ADMIN"
        }

        // Garantiza al menos un rol por defecto
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Si se almacena algún dato sensible temporal, se limpia aquí
    }

    /**
     * @return Collection<int, Role>
     */
    public function getRolesBbdd(): Collection
    {
        return $this->rolesBbdd;
    }

    public function addRoleBbdd(Role $role): self
    {
        if (!$this->rolesBbdd->contains($role)) {
            $this->rolesBbdd[] = $role;
        }
        return $this;
    }

    public function removeRoleBbdd(Role $role): self
    {
        $this->rolesBbdd->removeElement($role);
        return $this;
    }
}
