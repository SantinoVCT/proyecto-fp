<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Tipo;

class TipoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $admin = new Rol();
        $usuario = new Rol();

        $admin->setNombre("Admin");
        $usuario->setNombre("Usuario");

        $manager->persist($admin);
        $manager->persist($usuario);

        $manager->flush();

        $this->addReference('admin', $admin);
        $this->addReference('usuario', $usuario);

    }
}
