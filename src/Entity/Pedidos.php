<?php

namespace App\Entity;

use App\Repository\PedidosRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PedidosRepository::class)]
class Pedidos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $Cantidad = null;

    #[ORM\Column]
    private ?int $Estado = 0;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $FechaPedido = null;

    #[ORM\ManyToOne(inversedBy: 'pedidos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $Usuario = null;

    #[ORM\ManyToOne(inversedBy: 'pedidos')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Producto $Producto = null;

    #[ORM\Column]
    private ?int $CodigoPedido = null;

    public function __construct()
    {
        $this->FechaPedido = new \DateTime('now');
        $this->Estado = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCantidad(): ?int
    {
        return $this->Cantidad;
    }

    public function setCantidad(int $Cantidad): static
    {
        $this->Cantidad = $Cantidad;

        return $this;
    }

    public function getEstado(): ?int
    {
        return $this->Estado;
    }

    public function setEstado(int $Estado): static
    {
        $this->Estado = $Estado;

        return $this;
    }

    public function getFechaPedido(): ?\DateTime
    {
        return $this->FechaPedido;
    }

    public function setFechaPedido(\DateTime $FechaPedido): static
    {
        $this->FechaPedido = $FechaPedido;

        return $this;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->Usuario;
    }

    public function setUsuario(?Usuario $Usuario): static
    {
        $this->Usuario = $Usuario;

        return $this;
    }

    public function getProducto(): ?Producto
    {
        return $this->Producto;
    }

    public function setProducto(?Producto $Producto): static
    {
        $this->Producto = $Producto;

        return $this;
    }

    public function getCodigoPedido(): ?int
    {
        return $this->CodigoPedido;
    }

    public function setCodigoPedido(int $CodigoPedido): static
    {
        $this->CodigoPedido = $CodigoPedido;

        return $this;
    }
}
