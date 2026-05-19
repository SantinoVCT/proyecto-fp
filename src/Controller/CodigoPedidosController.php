<?php

namespace App\Controller;

use App\Entity\CodigoPedido;
use App\Form\CodigoPedidosForm;
use App\Repository\CodigoPedidoRepository;

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

#[Route('/homepage/DB/codigo-pedidos')]
final class CodigoPedidosController extends AbstractController
{
    #[Route(name: 'app_codigo_pedidos_index', methods: ['GET'])]
    public function index(CodigoPedidoRepository $codigoPedidoRepository, CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('codigo_pedidos/index.html.twig', [
            'codigo_pedidos' => $codigoPedidoRepository->findAll(),
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/new', name: 'app_codigo_pedidos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $Codigo = new CodigoPedido();
        $form = $this->createForm(CodigoPedidosForm::class, $Codigo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($Codigo);
            $entityManager->flush();

            return $this->redirectToRoute('app_codigo_pedidos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('codigo_pedidos/new.html.twig', [
            'codigo_pedido' => $Codigo,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/{id}', name: 'app_codigo_pedidos_show', methods: ['GET'])]
    public function show(CodigoPedido $codigoPedido, CarritoRepository $carritoRepository, PedidosRepository $pedidosRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));
        $pedidos = $pedidosRepository->findBy(['CodigoPedidoRelacion' => $codigoPedido->getId()]);

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('codigo_pedidos/show.html.twig', [
            'codigo_pedido' => $codigoPedido,
            'pedidos' => $pedidos,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_codigo_pedidos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CodigoPedido $codigoPedido, EntityManagerInterface $entityManager, CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $form = $this->createForm(CodigoPedidosForm::class, $codigoPedido);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_codigo_pedidos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('codigo_pedidos/edit.html.twig', [
            'codigo_pedido' => $codigoPedido,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
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
