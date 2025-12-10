<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriaRepository::class)]
class Categoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nombre = null;

    /**
     * @var Collection<int, Producto>
     */
    #[ORM\OneToMany(targetEntity: Producto::class, mappedBy: 'Categoria')]
    private Collection $productos;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $FechaCreada = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $FechaUpdate = null;

    public function __construct()
    {
        $this->productos = new ArrayCollection();
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

    /**
     * @return Collection<int, Producto>
     */
    public function getProductos(): Collection
    {
        return $this->productos;
    }

    public function addProducto(Producto $producto): static
    {
        if (!$this->productos->contains($producto)) {
            $this->productos->add($producto);
            $producto->setCategoria($this);
        }

        return $this;
    }

    public function removeProducto(Producto $producto): static
    {
        if ($this->productos->removeElement($producto)) {
            // set the owning side to null (unless already changed)
            if ($producto->getCategoria() === $this) {
                $producto->setCategoria(null);
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
