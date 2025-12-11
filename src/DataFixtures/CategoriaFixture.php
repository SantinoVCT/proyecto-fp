<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Categoria;

class CategoriaFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $categoria1 = new Categoria();
        $categoria2 = new Categoria();
        $categoria3 = new Categoria();
        $categoria4 = new Categoria();
        $categoria5 = new Categoria();
        $categoria6 = new Categoria();
        $categoria7 = new Categoria();
        $categoria8 = new Categoria();
        $categoria9 = new Categoria();
        $categoria10 = new Categoria();

        $categoria1->setNombre('Electrónica');
        $categoria1->setFechaCreada(new \DateTime());

        $this->addReference('categoria1', $categoria1);

        $categoria2->setNombre('Placas Base');
        $categoria2->setFechaCreada(new \DateTime());

        $this->addReference('categoria2', $categoria2);

        $categoria3->setNombre('Procesadores');
        $categoria3->setFechaCreada(new \DateTime());

        $this->addReference('categoria3', $categoria3);

        $categoria4->setNombre('Tarjetas Gráficas');
        $categoria4->setFechaCreada(new \DateTime());

        $this->addReference('categoria4', $categoria4);

        $categoria5->setNombre('Almacenamiento');
        $categoria5->setFechaCreada(new \DateTime());

        $this->addReference('categoria5', $categoria5);

        $categoria6->setNombre('Periféricos');
        $categoria6->setFechaCreada(new \DateTime());

        $this->addReference('categoria6', $categoria6);

        $categoria7->setNombre('Torres y Cajas');
        $categoria7->setFechaCreada(new \DateTime());

        $this->addReference('categoria7', $categoria7);

        $categoria8->setNombre('Monitores');
        $categoria8->setFechaCreada(new \DateTime());

        $this->addReference('categoria8', $categoria8);

        $categoria9->setNombre('Redes');
        $categoria9->setFechaCreada(new \DateTime());

        $this->addReference('categoria9', $categoria9);

        $categoria10->setNombre('Accesorios');
        $categoria10->setFechaCreada(new \DateTime());

        $this->addReference('categoria10', $categoria10);

        $manager->persist($categoria1);
        $manager->persist($categoria2);
        $manager->persist($categoria3);
        $manager->persist($categoria4);
        $manager->persist($categoria5);
        $manager->persist($categoria6);
        $manager->persist($categoria7);
        $manager->persist($categoria8);
        $manager->persist($categoria9);
        $manager->persist($categoria10);

        $manager->flush();
    }
}
