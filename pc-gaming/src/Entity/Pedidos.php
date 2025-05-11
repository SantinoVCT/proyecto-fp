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
    private ?int $Estado = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $FechaPedido = null;

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
}
