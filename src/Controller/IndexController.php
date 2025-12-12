<?php

namespace App\Controller;

use App\Entity\Pedidos;
use App\Form\BuscarPedido;
use App\Repository\PedidosRepository;

use App\Entity\Carrito;
use App\Form\CarritoForm;
use App\Form\CarritoCambiar;
use App\Form\CarritoAdd;
use App\Repository\CarritoRepository;

use App\Entity\Usuario;
use App\Form\UsuarioForm;
use App\Repository\UsuarioRepository;

use App\Entity\Producto;
use App\Form\ProductoForm;
use App\Repository\ProductoRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route(name: 'app_index', methods: ['GET'])]
    public function index(ProductoRepository $productoRepository): Response
    {
        return $this->render('index/index.html.twig', [
            'productos' => $productoRepository->findAll(),
        ]);
    }
    
    #[Route('/vista/{id}', name: 'producto_offline', methods: ['GET'])]
    public function show(Producto $producto): Response
    {
        return $this->render('index/vista.html.twig', [
            'producto' => $producto,
        ]);
    }

    #[Route('/homepage', name: 'app_homepage', methods: ['GET'])]
    public function homepage(ProductoRepository $productoRepository): Response
    {
        
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }


        return $this->render('index/iniciado/index.html.twig', [
            'productos' => $productoRepository->findAll(),
            'mostrarBoton' => $mostrarBoton,
        ]);
    }
    #[Route('/homepage/vista/{id}', name: 'producto_online', methods: ['GET'])]
    public function showOnline(Producto $producto): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('index/iniciado/vista.html.twig', [
            'producto' => $producto,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/homepage/carrito', name: 'carrito_online', methods: ['GET'])]
    public function carroOnline(CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('index/iniciado/carrito/carrito.html.twig', [
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
            'mostrarBoton' => $mostrarBoton,
            'idUser' => $idUser,
        ]);
    }

    #[Route('/homepage/pedidos',name: 'pedidos_online', methods: ['GET', 'POST'])]
    public function pedidoOnline(Request $request, PedidosRepository $pedidosRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $idUser = $user->getId();
        // $pedido = new Pedidos();
        // $form = $this->createForm(BuscarPedido::class, $pedido);

        $pedidos = $pedidosRepository->findBy(['Usuario' => $idUser]);

        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $pedidos = $pedidosRepository->findBy(['Usuario' => $idUser, 'CodigoPedido' => $pedido->getCodigoPedido()]);
        //     return $this->render('index/iniciado/pedido/index.html.twig', [
        //         'pedidos' => $pedidos,
        //         'form' => $form,
        //         'mostrarBoton' => $mostrarBoton,
        //     ]);
        // }else{
            return $this->render('index/iniciado/pedido/index.html.twig', [
                'pedidos' => $pedidos,
                // 'form' => $form,
                'mostrarBoton' => $mostrarBoton,
            ]);
        // }
    }
    

    #[Route('/homepage/carrito/anadir', name: 'app_anadir', methods: ['GET', 'POST'])]
    public function anadir(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        if ($user && in_array('ROLE_ADMIN', $user->getRoles())) {
            $mostrarBoton = true;
        }

        $carrito = new Carrito();
        $form = $this->createForm(CarritoAdd::class, $carrito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $carrito->setUsuario($user);

            $entityManager->persist($carrito);
            $entityManager->flush();

            return $this->redirectToRoute('carrito_online', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('index/iniciado/carrito/new.html.twig', [
            'carrito' => $carrito,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/homepage/carrito/cambiar/{id}', name: 'app_cambiar', methods: ['GET', 'POST'])]
    public function cambiar(Request $request, Carrito $carrito, EntityManagerInterface $entityManager): Response
    {   
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && in_array('ROLE_ADMIN', $user->getRoles())) {
            $mostrarBoton = true;
        }

        $form = $this->createForm(CarritoCambiar::class, $carrito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('carrito_online', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('index/iniciado/carrito/edit.html.twig', [
            'carrito' => $carrito,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/homepage/carrito/comprar', name: 'app_comprar', methods: ['GET', 'POST'])]
    public function comprar(Request $request, EntityManagerInterface $entityManager, CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $codigoPedido = rand(1000, 9999);

        $carritos = $carritoRepository->findBy(['Usuario' => $userId]);

        foreach ($carritos as $carrito) {
            $pedido = new Pedidos();
            $pedido->setEstado(0);
            $pedido->setCantidad($carrito->getCantidad());
            $pedido->setUsuario($carrito->getUsuario());
            $pedido->setProducto($carrito->getProducto());
            $pedido->setFechaPedido(new \DateTime('now'));
            $pedido->setCodigoPedido($codigoPedido);
            $entityManager->persist($pedido);

            $entityManager->remove($carrito);
        }
        
        $entityManager->flush();

        return $this->redirectToRoute('carrito_online');
        
    }

    #[Route('/homepage/carrito/quitar/{id}', name: 'app_quitar', methods: ['POST'])]
    public function delete(Request $request, Carrito $carrito, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carrito->getId(), $request->getPayload()->getString('_token'))) {
            try {
            $entityManager->remove($carrito);
            $entityManager->flush();
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash('error', 'No se puede borrar el carrito porque está relacionado con otros registros.');
                return $this->redirectToRoute('app_carrito_index');
            }
        }
        return $this->redirectToRoute('app_carrito_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/homepage/crud', name: 'app_index_crud', methods: ['GET'])]
    public function index_crud(ProductoRepository $productoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBotonAdmin = false;
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }


        if ($user && in_array('ROLE_ADMIN', $user->getRoles())) {
            $mostrarBotonAdmin = true;
        }

        return $this->render('index/iniciado/crud/index.html.twig', [
            'mostrarBotonAdmin' => $mostrarBotonAdmin,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }
}
