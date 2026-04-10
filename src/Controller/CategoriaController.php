<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Form\CategoriaForm;
use App\Repository\CategoriaRepository;

use App\Repository\CarritoRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/homepage/DB/categoria')]
final class CategoriaController extends AbstractController
{
    #[Route(name: 'app_categoria_index', methods: ['GET'])]
    public function index(CategoriaRepository $categoriaRepository, CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('categoria/index.html.twig', [
            'categorias' => $categoriaRepository->findAll(),
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/new', name: 'app_categoria_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $categorium = new Categoria();
        $form = $this->createForm(CategoriaForm::class, $categorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorium->setFechaCreada(new \DateTime());

            $entityManager->persist($categorium);
            $entityManager->flush();

            return $this->redirectToRoute('app_categoria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categoria/new.html.twig', [
            'categorium' => $categorium,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/{id}', name: 'app_categoria_show', methods: ['GET'])]
    public function show(Categoria $categorium, CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('categoria/show.html.twig', [
            'categorium' => $categorium,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_categoria_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categoria $categorium, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $form = $this->createForm(CategoriaForm::class, $categorium);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorium->setFechaUpdate(new \DateTime());

            $entityManager->flush();

            return $this->redirectToRoute('app_categoria_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('categoria/edit.html.twig', [
            'categorium' => $categorium,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/{id}', name: 'app_categoria_delete', methods: ['POST'])]
    public function delete(Request $request, Categoria $categorium, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorium->getId(), $request->getPayload()->getString('_token'))) {
            try {
                $entityManager->remove($categorium);
                $entityManager->flush();
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash('error', 'No se puede borrar el categorium porque está relacionado con otros registros.');
                return $this->redirectToRoute('app_categorium_index');
            }
        }

        return $this->redirectToRoute('app_categoria_index', [], Response::HTTP_SEE_OTHER);
    }
}
