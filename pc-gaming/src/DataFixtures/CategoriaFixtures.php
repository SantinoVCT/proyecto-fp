<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Categoria;

class CategoriaFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $Categoria1 = new Categoria();
        $Categoria2 = new Categoria();
        $Categoria3 = new Categoria();
        $Categoria4 = new Categoria();

        $Categoria1->setNombre('Categoria1');
        $Categoria2->setNombre('Categoria2');
        $Categoria3->setNombre('Categoria3');
        $Categoria4->setNombre('Categoria4');
        $manager->persist($Categoria1);
        $manager->persist($Categoria2);
        $manager->persist($Categoria3);
        $manager->persist($Categoria4);

        $manager->flush();

        $this->addReference('Categoria1',$Categoria1);
        $this->addReference('Categoria2',$Categoria2);
        $this->addReference('Categoria3',$Categoria3);
        $this->addReference('Categoria4',$Categoria4);
    }
}
