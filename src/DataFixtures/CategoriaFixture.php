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
        $this->addReference('categoria1', $categoria1);

        $categoria2->setNombre('Placas Base');
        $this->addReference('categoria2', $categoria2);

        $categoria3->setNombre('Procesadores');
        $this->addReference('categoria3', $categoria3);

        $categoria4->setNombre('Tarjetas Gráficas');
        $this->addReference('categoria4', $categoria4);

        $categoria5->setNombre('Almacenamiento');
        $this->addReference('categoria5', $categoria5);

        $categoria6->setNombre('Periféricos');
        $this->addReference('categoria6', $categoria6);

        $categoria7->setNombre('Torres y Cajas');
        $this->addReference('categoria7', $categoria7);

        $categoria8->setNombre('Monitores');
        $this->addReference('categoria8', $categoria8);

        $categoria9->setNombre('Redes');
        $this->addReference('categoria9', $categoria9);

        $categoria10->setNombre('Accesorios');
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
