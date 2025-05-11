<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Tipo;
use App\Entity\Usuario;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UsuarioFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $adminUser = new User();

        $adminUser->setNombre("Admin");
        $adminUser->setApellido("host");
        $adminUser->setEmail("admin@gmail.com");
        $adminUser->setPassword("1234");
        $adminUser->setRol($this->getReference('admin', Tipo::class));

        $manager->persist($adminUser);

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            TipoFixtures::class,
        ];
    }
}
