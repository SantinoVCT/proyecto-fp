<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 150)]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $apellidos = null;

    #[ORM\Column(length: 255)]
    private ?string $direccion = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $FechaCreada = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $FechaUpdate = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailVerificationToken = null;

    /**
     * @var Collection<int, CodigoPedido>
     */
    #[ORM\OneToMany(targetEntity: CodigoPedido::class, mappedBy: 'Cliente')]
    private Collection $codigoPedidos;

    public function __construct()
    {
        $this->codigoPedidos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->apellidos;
    }

    public function setApellidos(string $apellidos): static
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(string $direccion): static
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getFechaCreada(): ?\DateTime
    {
        return $this->FechaCreada;
    }

    public function setFechaCreada(?\DateTime $FechaCreada): static
    {
        $this->FechaCreada = $FechaCreada;

        return $this;
    }

    public function getFechaUpdate(): ?\DateTime
    {
        return $this->FechaUpdate;
    }

    public function setFechaUpdate(?\DateTime $FechaUpdate): static
    {
        $this->FechaUpdate = $FechaUpdate;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getEmailVerificationToken(): ?string
    {
        return $this->emailVerificationToken;
    }

    public function setEmailVerificationToken(?string $emailVerificationToken): static
    {
        $this->emailVerificationToken = $emailVerificationToken;

        return $this;
    }

    /**
     * @return Collection<int, CodigoPedido>
     */
    public function getCodigoPedidos(): Collection
    {
        return $this->codigoPedidos;
    }

    public function addCodigoPedido(CodigoPedido $codigoPedido): static
    {
        if (!$this->codigoPedidos->contains($codigoPedido)) {
            $this->codigoPedidos->add($codigoPedido);
            $codigoPedido->setCliente($this);
        }

        return $this;
    }

    public function removeCodigoPedido(CodigoPedido $codigoPedido): static
    {
        if ($this->codigoPedidos->removeElement($codigoPedido)) {
            // set the owning side to null (unless already changed)
            if ($codigoPedido->getCliente() === $this) {
                $codigoPedido->setCliente(null);
            }
        }

        return $this;
    }
}
