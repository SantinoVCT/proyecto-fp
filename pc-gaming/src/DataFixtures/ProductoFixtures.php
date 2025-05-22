<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use App\Entity\Categoria;
use App\Entity\Producto;


class ProductoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $producto1 = new Categoria();
        $producto1->setNombre('Producto 1');
        $producto1->setDescripcion('Producto 1');
        $producto1->setCaracteristicas('Producto 1');
        $producto1->setDisponible(true);
        $producto1->setPrecio(true);
        $producto1->setCategoria($this->getReference('Categoria1', Categoria::class));
        $manager->persist($producto1);
        
        $producto2 = new Categoria();
        $producto2->setNombre('Producto 1');
        $producto2->setDescripcion('Producto 1');
        $producto2->setCaracteristicas('Producto 1');
        $producto2->setDisponible(true);
        $producto2->setPrecio(true);
        $producto2->setCategoria($this->getReference('Categoria2', Categoria::class));
        $manager->persist($producto2);

        $producto3 = new Categoria();
        $producto3->setNombre('Producto 1');
        $producto3->setDescripcion('Producto 1');
        $producto3->setCaracteristicas('Producto 1');
        $producto3->setDisponible(true);
        $producto3->setPrecio(true);
        $producto3->setCategoria($this->getReference('Categoria3', Categoria::class));
        $manager->persist($producto3);

        $producto4 = new Categoria();
        $producto4->setNombre('Producto 1');
        $producto4->setDescripcion('Producto 1');
        $producto4->setCaracteristicas('Producto 1');
        $producto4->setDisponible(true);
        $producto4->setPrecio(true);
        $producto4->setCategoria($this->getReference('Categoria4', Categoria::class));
        $manager->persist($producto4);

        $manager->flush();
        // $this->getReference('Categoria1', Categoria::class);
    }
    
    public function getDependencies(): array
    {
        return [
            CategoriaFixtures::class,
        ];
    }
}
