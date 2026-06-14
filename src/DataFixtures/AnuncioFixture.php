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

        $anuncio1->setNombre('AMD Anuncio');
        $anuncio1->setImagen('AMD_Anuncio.png');
        $anuncio1->setActivo(true);

        $anuncio2->setNombre('Corsair Anuncio');
        $anuncio2->setImagen('Corsair_Anuncio.png');
        $anuncio2->setActivo(true);

        $anuncio3->setNombre('El Gato Anuncio');
        $anuncio3->setImagen('Elgato_Anuncio.png');
        $anuncio3->setActivo(true);


        $manager->persist($anuncio1);
        $manager->persist($anuncio2);
        $manager->persist($anuncio3);
        $manager->flush();
    }
}