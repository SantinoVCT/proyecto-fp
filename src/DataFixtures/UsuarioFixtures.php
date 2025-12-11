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
        $gestorUser = new Usuario();

        $adminUser->setNombre("Admin");
        $adminUser->setApellidos("host");
        $adminUser->setEmail("admin@gmail.com");
        $adminUser->setPassword($this->passwordHasher->hashPassword($adminUser, "1234"));
        $adminUser->setRoles(["ROLE_ADMIN"]);
        $adminUser->setDireccion('Direccion 1');
        $adminUser->setFechaCreada(new \DateTime());

        $clienteUser->setNombre("Cliente");
        $clienteUser->setApellidos("Pepe");
        $clienteUser->setEmail("cliente@gmail.com");
        $clienteUser->setPassword( $this->passwordHasher->hashPassword($clienteUser, "1234"));
        $clienteUser->setRoles(["ROLE_USER"]);
        $clienteUser->setDireccion('Direccion 2');
        $clienteUser->setFechaCreada(new \DateTime());

        $gestorUser->setNombre("Gestor");
        $gestorUser->setApellidos("user");
        $gestorUser->setEmail("gestor@gmail.com");
        $gestorUser->setPassword($this->passwordHasher->hashPassword($gestorUser, "1234"));
        $gestorUser->setRoles(["ROLE_GESTOR"]);
        $gestorUser->setDireccion('Direccion 3');
        $gestorUser->setFechaCreada(new \DateTime());

        $manager->persist($adminUser);
        $manager->persist($clienteUser);
        $manager->persist($gestorUser);

        $manager->flush();
    }
}
