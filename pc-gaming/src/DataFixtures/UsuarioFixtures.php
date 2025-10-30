<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use App\Entity\Usuario;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UsuarioFixtures extends Fixture
{

        public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}
    public function load(ObjectManager $manager): void
    {
        
        // $product = new Product();
        // $manager->persist($product);

        $adminUser = new Usuario();
        $clienteUser = new Usuario();

        $adminUser->setNombre("Admin");
        $adminUser->setApellidos("host");
        $adminUser->setEmail("admin@gmail.com");
        $adminUser->setPassword($this->passwordHasher->hashPassword($adminUser, "1234"));
        $adminUser->setRoles(["ROLE_ADMIN"]);
        $adminUser->setDireccion('Direccion 1');

        $clienteUser->setNombre("Cliente");
        $clienteUser->setApellidos("Pepe");
        $clienteUser->setEmail("cliente@gmail.com");
        $clienteUser->setPassword( $this->passwordHasher->hashPassword($clienteUser, "1234"));
        $clienteUser->setRoles(["ROLE_USER"]);
        $clienteUser->setDireccion('Direccion 2');


        $manager->persist($adminUser);
        $manager->persist($clienteUser);

        $manager->flush();
    }
}
