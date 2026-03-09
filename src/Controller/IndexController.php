<?php

namespace App\Controller;

use App\Entity\Pedidos;
use App\Form\BuscarPedido;
use App\Form\ProductoBuscar;
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

use App\Entity\Categoria;
use App\Form\CategoriaForm;
use App\Repository\CategoriaRepository;

use App\Entity\Anuncio;
use App\Form\AnuncioForm;
use App\Repository\AnuncioRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route(name: 'app_index', methods: ['GET', 'POST'])]
    public function index(Request $request, CategoriaRepository $categoriaRepository, ProductoRepository $productoRepository, AnuncioRepository $anuncioRepository): Response
    {
        $form = $this->createForm(ProductoBuscar::class);
        $num = $anuncioRepository->count([]);
        $RanNum = rand(1, $num);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $Producto = $data->getNombre();

            if (empty($Producto)) {
                return $this->redirectToRoute('app_index');
            }else{
                $productos = $productoRepository->findByString($Producto);
                return $this->render('index/buscar.html.twig', [
                    'productos' => $productos,
                    'busqueda' => $Producto,
                    'form' => $form,
                    'categorias' => $categoriaRepository->findAll(),
                ]);
            }
        }else{
            return $this->render('index/index.html.twig', [
                'productos' => $productoRepository->findAll(),
                'destacados' => $productoRepository->findBy(['Destacado' => true]),
                'anuncios' => $anuncioRepository->findAll(),
                'anucioRandom' => $RanNum,
                'categorias' => $categoriaRepository->findAll(),
                'form' => $form,
            ]);
        }
        return $this->render('index/index.html.twig', [
            'productos' => $productoRepository->findAll(),
        ]);
    }
    
    #[Route('/vista/{id}', name: 'producto_offline', methods: ['GET'])]
    public function show(Producto $producto, ProductoRepository $productoRepository): Response
    {
        return $this->render('index/vista.html.twig', [
            'producto' => $producto,
            'destacados' => $productoRepository->findBy(['Destacado' => true]),
        ]);
    }
    #[Route('/categoria/{id}', name: 'categoria_offline', methods: ['GET', 'POST'])]
    public function showCategoria(Request $request, Categoria $categoria, ProductoRepository $productoRepository, CategoriaRepository $categoriaRepository): Response
    {
        $form = $this->createForm(ProductoBuscar::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $Producto = $data->getNombre();

            if (empty($Producto)) {
                return $this->redirectToRoute('app_index');
            }else{
                $productos = $productoRepository->findByString($Producto);
                return $this->render('index/buscar.html.twig', [
                    'productos' => $productos,
                    'busqueda' => $Producto,
                    'form' => $form,
                    'categorias' => $categoriaRepository->findAll(),
                ]);
            }
        }else{
            return $this->render('index/categoria.html.twig', [
                'productos' => $productoRepository->findBy(['Categoria' => $categoria->getId()]),
                'categoria' => $categoria,
                'categorias' => $categoriaRepository->findAll(),
                'form' => $form,
            ]);
        }
    }

    #[Route('/homepage', name: 'app_homepage', methods: ['GET', 'POST'])]
    public function homepage(Request $request, CategoriaRepository $categoriaRepository, ProductoRepository $productoRepository, AnuncioRepository $anuncioRepository): Response
    {
        
        $user = $this->getUser();
        $mostrarBoton = false;
        $num = $anuncioRepository->count([]);
        $RanNum = rand(1, $num);

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $form = $this->createForm(ProductoBuscar::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $Producto = $data->getNombre();

            if (empty($Producto)) {
                return $this->redirectToRoute('app_homepage');
            }else{
                $productos = $productoRepository->findByString($Producto);

                return $this->render('index/iniciado/buscar.html.twig', [
                    'productos' => $productos,
                    'form' => $form,
                    'busqueda' => $Producto,
                    'mostrarBoton' => $mostrarBoton,
                    'categorias' => $categoriaRepository->findAll(),
                ]);
            }
        }else{
            return $this->render('index/iniciado/index.html.twig', [
                'productos' => $productoRepository->findAll(),
                'destacados' => $productoRepository->findBy(['Destacado' => true]),
                'anuncios' => $anuncioRepository->findAll(),
                'anucioRandom' => $RanNum,
                'form' => $form,
                'mostrarBoton' => $mostrarBoton,
                'categorias' => $categoriaRepository->findAll(),
            ]);
        }

        
    }
    
    #[Route('/homepage/vista/{id}', name: 'producto_online', methods: ['GET'])]
    public function showOnline(Producto $producto, ProductoRepository $productoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('index/iniciado/vista.html.twig', [
            'producto' => $producto,
            'mostrarBoton' => $mostrarBoton,
            'destacados' => $productoRepository->findBy(['Destacado' => true]),
        ]);
    }

    #[Route('/homepage/categoria/{id}', name: 'categoria_online', methods: ['GET', 'POST'])]
    public function showCategoriaOnline(Request $request, Categoria $categoria, ProductoRepository $productoRepository, CategoriaRepository $categoriaRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $form = $this->createForm(ProductoBuscar::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $Producto = $data->getNombre();

            if (empty($Producto)) {
                return $this->redirectToRoute('app_index');
            }else{
                $productos = $productoRepository->findByString($Producto);
                return $this->render('index/iniciado/buscar.html.twig', [
                    'productos' => $productos,
                    'busqueda' => $Producto,
                    'form' => $form,
                    'mostrarBoton' => $mostrarBoton,
                    'categorias' => $categoriaRepository->findAll(),
                ]);
            }
        }else{
            return $this->render('index/iniciado/categoria.html.twig', [
                'productos' => $productoRepository->findBy(['Categoria' => $categoria->getId()]),
                'categoria' => $categoria,
                'categorias' => $categoriaRepository->findAll(),
                'mostrarBoton' => $mostrarBoton,
                'form' => $form,
            ]);
        }
    }

    // Trabajando en esto
    #[Route('/homepage/add/{id}', name: 'add_carrito', methods: ['GET'])]
    public function addToCart(Producto $producto, CarritoRepository $carritoRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $encontrado = $carritoRepository->findOneBy(['Usuario' => $user, 'Producto' => $producto]);
        if($encontrado){
            if ($encontrado->getCantidad() >= 99) {
            $maximo = true;
            } else {
                $maximo = false;
            }
        }

        if ($encontrado){
            if ($maximo){
                $this->addFlash('mensaje', 'No se pueden añadir más ' . $producto->getNombre() . ' al carrito.');
            } else {
                $encontrado->setCantidad($encontrado->getCantidad() + 1);
                $entityManager->persist($encontrado);
                $entityManager->flush();
                $this->addFlash('mensaje', 'Se ha añadido más ' . $producto->getNombre() . ' al carrito.');
            }
        } else{
            $carritoItem = new Carrito();
            $carritoItem->setUsuario($user);
            $carritoItem->setProducto($producto);
            $carritoItem->setCantidad(1);
            $entityManager->persist($carritoItem);
            $entityManager->flush();
            $this->addFlash('mensaje', 'Se ha añadido ' . $producto->getNombre() . ' al carrito.');
        }

        return $this->redirectToRoute('app_homepage');
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
        $form = $this->createForm(BuscarPedido::class);

        $pedidos = $pedidosRepository->findBy(['Usuario' => $idUser]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $codigoPedido = $data->getCodigoPedido();

            $pedidos = $pedidosRepository->findBy(['Usuario' => $idUser, 'CodigoPedido' => $codigoPedido]);
            if (empty($pedidos)) {
                $this->addFlash('error', 'No se encontraron pedidos con el código proporcionado.');
            }
            return $this->render('index/iniciado/pedido/index.html.twig', [
                'pedidos' => $pedidos,
                'form' => $form,
                'mostrarBoton' => $mostrarBoton,
            ]);
        }else{
            return $this->render('index/iniciado/pedido/index.html.twig', [
                'pedidos' => $pedidos,
                'form' => $form,
                'mostrarBoton' => $mostrarBoton,
            ]);
        }
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

        $this->addFlash('mensaje', 'Se a comprado sus productos. El codigo de Pedido es ' . $codigoPedido);
        return $this->redirectToRoute('carrito_online');
        
    }

    #[Route('/homepage/carrito/quitar/{id}', name: 'app_quitar', methods: ['POST'])]
    public function quitar(Request $request, Carrito $carrito, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$carrito->getId(), $request->getPayload()->getString('_token'))) {
            try {
            $entityManager->remove($carrito);
            $entityManager->flush();
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash('error', 'No se puede borrar el carrito porque está relacionado con otros registros.');
                return $this->redirectToRoute('carrito_online');
            }
        }
        return $this->redirectToRoute('carrito_online', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/homepage/DB', name: 'app_index_crud', methods: ['GET'])]
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
