<?php

namespace App\Controller;

use App\Entity\Pedidos;
use App\Form\PedidosForm;
use App\Repository\PedidosRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/homepage/crud/pedidos')]
final class PedidosController extends AbstractController
{
    #[Route(name: 'app_pedidos_index', methods: ['GET'])]
    public function index(PedidosRepository $pedidosRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('pedidos/index.html.twig', [
            'pedidos' => $pedidosRepository->findAll(),
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/new', name: 'app_pedidos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $pedido = new Pedidos();
        $form = $this->createForm(PedidosForm::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pedido);
            $entityManager->flush();

            return $this->redirectToRoute('app_pedidos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pedidos/new.html.twig', [
            'pedido' => $pedido,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/{id}', name: 'app_pedidos_show', methods: ['GET'])]
    public function show(Pedidos $pedido): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('pedidos/show.html.twig', [
            'pedido' => $pedido,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pedidos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pedidos $pedido, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $form = $this->createForm(PedidosForm::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pedidos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pedidos/edit.html.twig', [
            'pedido' => $pedido,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/{id}', name: 'app_pedidos_delete', methods: ['POST'])]
    public function delete(Request $request, Pedidos $pedido, EntityManagerInterface $entityManager): Response
    {
       
        if ($this->isCsrfTokenValid('delete'.$pedido->getId(), $request->getPayload()->getString('_token'))) {
            try {
                $entityManager->remove($pedido);
                $entityManager->flush();
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash('error', 'No se puede borrar el pedido porque está relacionado con otros registros.');
                return $this->redirectToRoute('app_pedidos_index');
            }
        }

        return $this->redirectToRoute('app_pedidos_index', [], Response::HTTP_SEE_OTHER);
    }
}
