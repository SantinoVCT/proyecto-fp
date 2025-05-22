<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Usuario;


class UsuarioFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        // $product = new Product();
        // $manager->persist($product);

        $adminUser = new Usuario();
        $clienteUser = new Usuario();

        $adminUser->setNombre("Admin");
        $adminUser->setApellidos("host");
        $adminUser->setEmail("admin@gmail.com");
        $adminUser->setPassword("1234");
        $adminUser->setRoles(['Administrador']);
        $adminUser->setDireccion('Direccion 1');

        $clienteUser->setNombre("Cliente");
        $clienteUser->setApellidos("Pepe");
        $clienteUser->setEmail("cliente@gmail.com");
        $clienteUser->setPassword("1234");
        $adminUser->setRoles(['Cliente']);
        $clienteUser->setDireccion('Direccion 2');


        $manager->persist($adminUser);
        $manager->persist($clienteUser);

        $manager->flush();
    }
}
