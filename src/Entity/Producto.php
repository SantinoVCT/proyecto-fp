<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductoRepository::class)]
class Producto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nombre = null;

    #[ORM\Column(length: 255)]
    private ?string $Descripcion = null;

    #[ORM\Column(length: 255)]
    private ?string $Caracteristicas = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $Requisitos = null;

    #[ORM\Column]
    private ?bool $Disponible = null;

    #[ORM\Column]
    private ?float $Precio = null;

    #[ORM\ManyToOne(inversedBy: 'productos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Categoria $Categoria = null;

    /**
     * @var Collection<int, Carrito>
     */
    #[ORM\OneToMany(targetEntity: Carrito::class, mappedBy: 'Producto')]
    private Collection $carritos;

    /**
     * @var Collection<int, Pedidos>
     */
    #[ORM\OneToMany(targetEntity: Pedidos::class, mappedBy: 'Producto')]
    private Collection $pedidos;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $FechaCreada = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $FechaUpdate = null;

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

    public function getDescripcion(): ?string
    {
        return $this->Descripcion;
    }

    public function setDescripcion(string $Descripcion): static
    {
        $this->Descripcion = $Descripcion;

        return $this;
    }

    public function getCaracteristicas(): ?string
    {
        return $this->Caracteristicas;
    }

    public function setCaracteristicas(string $Caracteristicas): static
    {
        $this->Caracteristicas = $Caracteristicas;

        return $this;
    }

    public function getRequisitos(): ?string
    {
        return $this->Requisitos;
    }

    public function setRequisitos(?string $Requisitos): static
    {
        $this->Requisitos = $Requisitos;

        return $this;
    }

    public function isDisponible(): ?bool
    {
        return $this->Disponible;
    }

    public function setDisponible(bool $Disponible): static
    {
        $this->Disponible = $Disponible;

        return $this;
    }

    public function getPrecio(): ?float
    {
        return $this->Precio;
    }

    public function setPrecio(float $Precio): static
    {
        $this->Precio = $Precio;

        return $this;
    }

    public function getCategoria(): ?Categoria
    {
        return $this->Categoria;
    }

    public function setCategoria(?Categoria $Categoria): static
    {
        $this->Categoria = $Categoria;

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
            $carrito->setProducto($this);
        }

        return $this;
    }

    public function removeCarrito(Carrito $carrito): static
    {
        if ($this->carritos->removeElement($carrito)) {
            // set the owning side to null (unless already changed)
            if ($carrito->getProducto() === $this) {
                $carrito->setProducto(null);
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
            $pedido->setProducto($this);
        }

        return $this;
    }

    public function removePedido(Pedidos $pedido): static
    {
        if ($this->pedidos->removeElement($pedido)) {
            // set the owning side to null (unless already changed)
            if ($pedido->getProducto() === $this) {
                $pedido->setProducto(null);
            }
        }

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
}
