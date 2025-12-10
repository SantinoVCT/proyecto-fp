<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\UsuarioForm;
use App\Repository\UsuarioRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/homepage/crud/admin/usuario')]
final class UsuarioController extends AbstractController
{
    #[Route(name: 'app_usuario_index', methods: ['GET'])]
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('usuario/index.html.twig', [
            'usuarios' => $usuarioRepository->findAll(),
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/new', name: 'app_usuario_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $usuario = new Usuario();
        $form = $this->createForm(UsuarioForm::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // If password field was filled, hash and set it
            $plain = $form->get('password')->getData();
            if (!empty($plain)) {
                $usuario->setPassword($passwordHasher->hashPassword($usuario, $plain));
            }

            $entityManager->persist($usuario);
            $entityManager->flush();

            return $this->redirectToRoute('app_usuario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('usuario/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/{id}', name: 'app_usuario_show', methods: ['GET'])]
    public function show(Usuario $usuario): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('usuario/show.html.twig', [
            'usuario' => $usuario,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_usuario_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Usuario $usuario, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $form = $this->createForm(UsuarioForm::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // If password field was filled, hash and set it
            $plain = $form->get('password')->getData();
            if (!empty($plain)) {
                $usuario->setPassword($passwordHasher->hashPassword($usuario, $plain));
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_usuario_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('usuario/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/{id}', name: 'app_usuario_delete', methods: ['POST'])]
    public function delete(Request $request, Usuario $usuario, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->getPayload()->getString('_token'))) {
            try {
                $entityManager->remove($usuario);
                $entityManager->flush();
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash('error', 'No se puede borrar el usuario porque está relacionado con otros registros.');
                return $this->redirectToRoute('app_usuario_index');
            }
        }
        return $this->redirectToRoute('app_usuario_index', [], Response::HTTP_SEE_OTHER);
    }
}
