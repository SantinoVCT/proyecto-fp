<?php

namespace App\Entity;

use App\Repository\ProductoRepository;
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
}
