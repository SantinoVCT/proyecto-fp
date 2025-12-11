<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Producto;
use App\Entity\Categoria;

class ProductoFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $producto1 = new Producto();
        $producto2 = new Producto();
        $producto3 = new Producto();
        $producto4 = new Producto();
        $producto5 = new Producto();
        $producto6 = new Producto();

        $producto1->setNombre('Smartphone XYZ');
        $producto1->setDescripcion('Un smartphone de última generación con pantalla OLED y cámara de alta resolución.');
        $producto1->setPrecio(699.99);
        $producto1->setDisponible(true);
        $producto1->setCaracteristicas('Pantalla: 6.5 pulgadas, Procesador: Octa-core, RAM: 8GB, Almacenamiento: 128GB');
        $producto1->setCategoria($this->getReference('categoria1', Categoria::class));
        $producto1->setFechaCreada(new \DateTime());

        $producto2->setNombre('Portátil ABC');
        $producto2->setDescripcion('Portátil ligero y potente, ideal para profesionales y estudiantes.');
        $producto2->setPrecio(1199.49);
        $producto2->setDisponible(true);
        $producto2->setCaracteristicas('Pantalla: 15.6 pulgadas, Procesador: Intel i7, RAM: 16GB, Almacenamiento: 512GB SSD');
        $producto2->setCategoria($this->getReference('categoria1', Categoria::class));
        $producto2->setFechaCreada(new \DateTime());

        $producto3->setNombre('Placa Base ASUS TUF GAMING B650-PLUS');
        $producto3->setDescripcion('Placa base robusta y fiable para gamers y entusiastas del hardware.');
        $producto3->setPrecio(199.99);
        $producto3->setDisponible(true);
        $producto3->setCaracteristicas('Socket: AM5, Factor de forma: ATX, Chipset: B650, RAM: Hasta 128GB DDR5');
        $producto3->setCategoria($this->getReference('categoria2', Categoria::class));
        $producto3->setFechaCreada(new \DateTime());

        $producto4->setNombre('Procesador AMD Ryzen 5 7600X');
        $producto4->setDescripcion('Procesador de alto rendimiento para gaming y tareas intensivas.');
        $producto4->setPrecio(299.99);
        $producto4->setDisponible(true);
        $producto4->setCaracteristicas('Núcleos: 6, Hilos: 12, Frecuencia base: 4.7GHz, Frecuencia turbo: 5.3GHz');
        $producto4->setCategoria($this->getReference('categoria3', Categoria::class));
        $producto4->setFechaCreada(new \DateTime());

        $producto5->setNombre('Disco Duro Seagate Game Drive PS5 1TB');
        $producto5->setDescripcion('Disco duro externo optimizado para PlayStation 5.');
        $producto5->setPrecio(119.99);
        $producto5->setDisponible(true);
        $producto5->setCaracteristicas('Capacidad: 1TB, Velocidad de transferencia: 140MB/s, Conectividad: USB 3.2');
        $producto5->setCategoria($this->getReference('categoria5', Categoria::class));
        $producto5->setFechaCreada(new \DateTime());

        $producto6->setNombre('Ratón Gaming Razer DeathAdder V2');
        $producto6->setDescripcion('Ratón ergonómico con alta precisión para gamers.');
        $producto6->setPrecio(69.99);
        $producto6->setDisponible(true);
        $producto6->setCaracteristicas('Sensor: Óptico 20K DPI, Botones: 8 programables, Iluminación: RGB Chroma');
        $producto6->setCategoria($this->getReference('categoria6', Categoria::class));
        $producto6->setFechaCreada(new \DateTime());

        $manager->persist($producto1);
        $manager->persist($producto2);
        $manager->persist($producto3);
        $manager->persist($producto4);
        $manager->persist($producto5);
        $manager->persist($producto6);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoriaFixture::class,
        ];
    }
}
