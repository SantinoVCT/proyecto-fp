<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Anuncio;

class AnuncioFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $anuncio1 = new Anuncio();
        $anuncio2 = new Anuncio(); 
        $anuncio3 = new Anuncio();

        $anuncio1->setNombre('Anuncio 1');
        $anuncio1->setImagen('anuncio1.png');
        $anuncio1->setActivo(true);

        $anuncio2->setNombre('Anuncio 2');
        $anuncio2->setImagen('anuncio2.png');
        $anuncio2->setActivo(true);

        $anuncio3->setNombre('Anuncio 3');
        $anuncio3->setImagen('anuncio3.png');
        $anuncio3->setActivo(true);


        $manager->persist($anuncio1);
        $manager->persist($anuncio2);
        $manager->persist($anuncio3);
        $manager->flush();
    }
}