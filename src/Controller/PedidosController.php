<?php

namespace App\Controller;

use App\Entity\CodigoPedido;

use App\Entity\Pedidos;
use App\Form\PedidosForm;
use App\Repository\PedidosRepository;

use App\Repository\CarritoRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/homepage/DB/pedidos')]
final class PedidosController extends AbstractController
{
    // #[Route(name: 'app_pedidos_index', methods: ['GET'])]
    // public function index(PedidosRepository $pedidosRepository, CarritoRepository $carritoRepository): Response
    // {
    //     $user = $this->getUser();
    //     $mostrarBoton = false;
    //     $idUser = $user->getId();

    //     $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

    //     if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
    //         $mostrarBoton = true;
    //     }

    //     return $this->render('pedidos/index.html.twig', [
    //         'pedidos' => $pedidosRepository->findAll(),
    //         'mostrarBoton' => $mostrarBoton,
    //         'carro_num' => $numero_carro,
    //         'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
    //     ]);
    // }

    #[Route('/{id}/new', name: 'app_pedidos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CodigoPedido $codigoPedido, EntityManagerInterface $entityManager, CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $pedido = new Pedidos();

        $pedido->setCodigoPedidoRelacion($codigoPedido);
        $pedido->setUsuario($codigoPedido->getCliente());
        $pedido->setCodigoPedido($codigoPedido->getCodigo());
        $form = $this->createForm(PedidosForm::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pedido);
            $entityManager->flush();

            return $this->redirectToRoute('app_codigo_pedidos_show', ['id' => $codigoPedido->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pedidos/new.html.twig', [
            'pedido' => $pedido,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'codigoPedido' => $codigoPedido,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/{id}', name: 'app_pedidos_show', methods: ['GET'])]
    public function show(Pedidos $pedido, CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('pedidos/show.html.twig', [
            'pedido' => $pedido,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pedidos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pedidos $pedido, EntityManagerInterface $entityManager, CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $form = $this->createForm(PedidosForm::class, $pedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pedidos_show', ['id' => $pedido->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pedidos/edit.html.twig', [
            'pedido' => $pedido,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/{id}', name: 'app_pedidos_delete', methods: ['POST'])]
    public function delete(Request $request, Pedidos $pedido, EntityManagerInterface $entityManager): Response
    {
        $codigoPedido = $pedido->getCodigoPedidoRelacion();
       
        if ($this->isCsrfTokenValid('delete'.$pedido->getId(), $request->getPayload()->getString('_token'))) {
            try {
                $entityManager->remove($pedido);
                $entityManager->flush();
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash('error', 'No se puede borrar el pedido porque está relacionado con otros registros.');
                return $this->redirectToRoute('app_codigo_pedidos_show', ['id' => $codigoPedido->getId()]);
            }
        }

        return $this->redirectToRoute('app_codigo_pedidos_show', ['id' => $codigoPedido->getId()], Response::HTTP_SEE_OTHER);
    }
}
