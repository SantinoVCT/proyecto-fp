<?php

namespace App\Controller;

use App\Entity\CodigoPedido;
use App\Form\CodigoPedidoForm;
use App\Repository\CodigoPedidoRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/homepage/DB/codigo-pedidos')]
final class CodigoPedidosController extends AbstractController
{
    #[Route(name: 'app_codigo_pedidos_index', methods: ['GET'])]
    public function index(CodigoPedidoRepository $codigoPedidoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('codigo_pedidos/index.html.twig', [
            'codigo_pedidos' => $codigoPedidoRepository->findAll(),
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/new', name: 'app_codigo_pedidos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $pedido = new CodigoPedido();
        $form = $this->createForm(CodigoPedidoForm::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pedido);
            $entityManager->flush();

            return $this->redirectToRoute('app_codigo_pedidos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('codigo_pedidos/new.html.twig', [
            'codigo_pedido' => $pedido,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/{id}', name: 'app_codigo_pedidos_show', methods: ['GET'])]
    public function show(CodigoPedido $codigoPedido): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('codigo_pedidos/show.html.twig', [
            'codigo_pedido' => $codigoPedido,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_codigo_pedidos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CodigoPedido $codigoPedido, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $form = $this->createForm(CodigoPedidoForm::class, $codigoPedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_codigo_pedidos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('codigo_pedidos/edit.html.twig', [
            'codigo_pedido' => $codigoPedido,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/{id}', name: 'app_codigo_pedidos_delete', methods: ['POST'])]
    public function delete(Request $request, CodigoPedido $codigoPedido, EntityManagerInterface $entityManager): Response
    {
       
        if ($this->isCsrfTokenValid('delete'.$codigoPedido->getId(), $request->getPayload()->getString('_token'))) {
            try {
                $entityManager->remove($codigoPedido);
                $entityManager->flush();
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash('error', 'No se puede borrar el código de pedido porque está relacionado con otros registros.');
                return $this->redirectToRoute('app_codigo_pedidos_index');
            }
        }

        return $this->redirectToRoute('app_codigo_pedidos_index', [], Response::HTTP_SEE_OTHER);
    }
}
