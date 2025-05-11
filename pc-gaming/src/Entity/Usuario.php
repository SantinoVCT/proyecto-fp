<?php

namespace App\Entity;

use App\Repository\UsuarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UsuarioRepository::class)]
class Usuario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $Apellidos = null;

    #[ORM\Column(length: 255)]
    private ?string $Contraseña = null;

    #[ORM\Column(length: 255)]
    private ?string $Email = null;

    #[ORM\ManyToOne(inversedBy: 'usuarios')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Tipo $Tipo = null;

    /**
     * @var Collection<int, Carrito>
     */
    #[ORM\OneToMany(targetEntity: Carrito::class, mappedBy: 'Usuario')]
    private Collection $carritos;

    /**
     * @var Collection<int, Pedidos>
     */
    #[ORM\OneToMany(targetEntity: Pedidos::class, mappedBy: 'Usuario')]
    private Collection $pedidos;

    public function __construct()
    {
        $this->carritos = new ArrayCollection();
        $this->pedidos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->Nombre;
    }

    public function setNombre(string $Nombre): static
    {
        $this->Nombre = $Nombre;

        return $this;
    }

    public function getApellidos(): ?string
    {
        return $this->Apellidos;
    }

    public function setApellidos(string $Apellidos): static
    {
        $this->Apellidos = $Apellidos;

        return $this;
    }

    public function getContraseña(): ?string
    {
        return $this->Contraseña;
    }

    public function setContraseña(string $Contraseña): static
    {
        $this->Contraseña = $Contraseña;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getTipo(): ?Tipo
    {
        return $this->Tipo;
    }

    public function setTipo(?Tipo $Tipo): static
    {
        $this->Tipo = $Tipo;

        return $this;
    }

    /**
     * @return Collection<int, Carrito>
     */
    public function getCarritos(): Collection
    {
        return $this->carritos;
    }

    public function addCarrito(Carrito $carrito): static
    {
        if (!$this->carritos->contains($carrito)) {
            $this->carritos->add($carrito);
            $carrito->setUsuario($this);
        }

        return $this;
    }

    public function removeCarrito(Carrito $carrito): static
    {
        if ($this->carritos->removeElement($carrito)) {
            // set the owning side to null (unless already changed)
            if ($carrito->getUsuario() === $this) {
                $carrito->setUsuario(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Pedidos>
     */
    public function getPedidos(): Collection
    {
        return $this->pedidos;
    }

    public function addPedido(Pedidos $pedido): static
    {
        if (!$this->pedidos->contains($pedido)) {
            $this->pedidos->add($pedido);
            $pedido->setUsuario($this);
        }

        return $this;
    }

    public function removePedido(Pedidos $pedido): static
    {
        if ($this->pedidos->removeElement($pedido)) {
            // set the owning side to null (unless already changed)
            if ($pedido->getUsuario() === $this) {
                $pedido->setUsuario(null);
            }
        }

        return $this;
    }
}
