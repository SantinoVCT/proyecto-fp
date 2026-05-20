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
use App\Form\InfoUsuarioForm;
use App\Form\UsuarioForm;
use App\Repository\UsuarioRepository;

use App\Entity\Producto;
use App\Form\ProductoForm;
use App\Repository\ProductoRepository;

use App\Entity\Categoria;
use App\Form\CategoriaForm;
use App\Repository\CategoriaRepository;

use App\Entity\CodigoPedido;
use App\Form\CodigoPedidoForm;
use App\Repository\CodigoPedidoRepository;

use App\Entity\Anuncio;
use App\Form\AnuncioForm;
use App\Repository\AnuncioRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class IndexController extends AbstractController
{
    #[Route(name: 'app_index', methods: ['GET', 'POST'])]
    public function index(Request $request, CategoriaRepository $categoriaRepository, ProductoRepository $productoRepository, AnuncioRepository $anuncioRepository): Response
    {
        $form = $this->createForm(ProductoBuscar::class);
        $num = $anuncioRepository->count([]);

        $anuncio = $anuncioRepository->findAll();
        if (count($anuncio) > 0) {
            $Anuncio_aleatorio = $anuncio[array_rand($anuncio)];
        }

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
                    'destacados' => $productoRepository->findBy(['Destacado' => true]),
                    'form' => $form,
                    'categorias' => $categoriaRepository->findAll(),
                ]);
            }
        }else{
            return $this->render('index/index.html.twig', [
                'productos' => $productoRepository->findAllTheOffertes(),
                'destacados' => $productoRepository->findBy(['Destacado' => true]),
                'anuncios' => $anuncioRepository->findAll(),
                'anucioRandom' => $Anuncio_aleatorio,
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
                    'destacados' => $productoRepository->findBy(['Destacado' => true]),
                    'categorias' => $categoriaRepository->findAll(),
                ]);
            }
        }else{
            return $this->render('index/categoria.html.twig', [
                'productos' => $productoRepository->findBy(['Categoria' => $categoria->getId()]),
                'categoria' => $categoria,
                'categorias' => $categoriaRepository->findAll(),
                'destacados' => $productoRepository->findBy(['Destacado' => true]),
                'form' => $form,
            ]);
        }
    }

    #[Route('/homepage', name: 'app_homepage', methods: ['GET', 'POST'])]
    public function homepage(Request $request, CategoriaRepository $categoriaRepository, CarritoRepository $carritoRepository, ProductoRepository $productoRepository, AnuncioRepository $anuncioRepository): Response
    {     
        $user = $this->getUser();
        $mostrarBoton = false;
        $num = $anuncioRepository->count([]);
        
        $anuncio = $anuncioRepository->findAll();
        if (count($anuncio) > 0) {
            $Anuncio_aleatorio = $anuncio[array_rand($anuncio)];
        }

        $idUser = $user->getId();
        
        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));
   
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
                    'destacados' => $productoRepository->findBy(['Destacado' => true]),
                    'carro_num' => $numero_carro,
                    'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
                ]);
            }
        }else{
            return $this->render('index/iniciado/index.html.twig', [
                'productos' => $productoRepository->findAllTheOffertes(),
                'destacados' => $productoRepository->findBy(['Destacado' => true]),
                'anuncios' => $anuncioRepository->findAll(),
                'form' => $form,
                'mostrarBoton' => $mostrarBoton,
                'anucioRandom' => $Anuncio_aleatorio,
                'categorias' => $categoriaRepository->findAll(),
                'carro_num' => $numero_carro,
                'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
            ]);
        }

        
    }
    
    #[Route('/homepage/mi_cuenta', name: 'app_cuenta', methods: ['GET', 'POST'])]
    public function cuenta(Request $request, CategoriaRepository $categoriaRepository, CarritoRepository $carritoRepository): Response
    {     
        $user = $this->getUser();
        $mostrarBoton = false;

        $idUser = $user->getId();
        
        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));
   
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
                    'carro_num' => $numero_carro,
                    'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
                ]);
            }
        }else{
            return $this->render('index/iniciado/usuario/index.html.twig', [
                'usuario' => $user,
                'form' => $form,
                'mostrarBoton' => $mostrarBoton,
                'categorias' => $categoriaRepository->findAll(),
                'carro_num' => $numero_carro,
                'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
            ]);
        }
        

        
    }

    #[Route('/homepage/mi_cuenta/edit', name: 'app_cuenta_edit', methods: ['GET', 'POST'])]
    public function cuentaEdit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, CategoriaRepository $categoriaRepository, CarritoRepository $carritoRepository): Response
    {     
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $form = $this->createForm(InfoUsuarioForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // If password field was filled, hash and set it
            $plain = $form->get('password')->getData();
            if (!empty($plain)) {
                $user->setPassword($passwordHasher->hashPassword($user, $plain));
            }

            $user->setFechaUpdate(new \DateTime());

            $entityManager->flush();

            return $this->redirectToRoute('app_cuenta', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('index/iniciado/usuario/edit.html.twig', [
            'usuario' => $user,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/homepage/vista/{id}', name: 'producto_online', methods: ['GET'])]
    public function showOnline(Producto $producto, ProductoRepository $productoRepository, CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('index/iniciado/vista.html.twig', [
            'producto' => $producto,
            'mostrarBoton' => $mostrarBoton,
            'destacados' => $productoRepository->findBy(['Destacado' => true]),
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/homepage/categoria/{id}', name: 'categoria_online', methods: ['GET', 'POST'])]
    public function showCategoriaOnline(Request $request, CarritoRepository $carritoRepository, Categoria $categoria, ProductoRepository $productoRepository, CategoriaRepository $categoriaRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

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
                    'destacados' => $productoRepository->findBy(['Destacado' => true]),
                    'categorias' => $categoriaRepository->findAll(),
                    'carro_num' => $numero_carro,
                    'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
                ]);
            }
        }else{
            return $this->render('index/iniciado/categoria.html.twig', [
                'productos' => $productoRepository->findBy(['Categoria' => $categoria->getId()]),
                'categoria' => $categoria,
                'categorias' => $categoriaRepository->findAll(),
                'mostrarBoton' => $mostrarBoton,
                'form' => $form,
                'destacados' => $productoRepository->findBy(['Destacado' => true]),
                'carro_num' => $numero_carro,
                'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
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
            if ($encontrado->getCantidad() >= 99 || $encontrado->getCantidad() >= $producto->getStock()) {
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
        $Total_precio = 0;
        $carro = $carritoRepository->findBy(['Usuario' => $idUser]);
        foreach($carro as $producto){
            if($producto->getProducto()->getDescuento()){
                $Total_precio+= $producto->getCantidad()*($producto->getProducto()->getPrecio()*(1-($producto->getProducto()->getDescuento()/100)));
            }else{
                $Total_precio+= $producto->getCantidad()*$producto->getProducto()->getPrecio();
            }
        }

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));


        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('index/iniciado/carrito/carrito.html.twig', [
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
            'mostrarBoton' => $mostrarBoton,
            'idUser' => $idUser,
            'carro_num' => $numero_carro,
            'Total_precio_carrito' => $Total_precio,
        ]);
    }

    #[Route('/homepage/pedidos',name: 'pedidos_online', methods: ['GET', 'POST'])]
    public function pedidoOnline(Request $request, CarritoRepository $carritoRepository, CodigoPedidoRepository $CodigoPedidoRepository): Response
    {
        

        $user = $this->getUser();
        $mostrarBoton = false;
        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        $pedidos = $CodigoPedidoRepository->findBy(['Cliente' => $idUser]);
        
        return $this->render('index/iniciado/pedido/index.html.twig', [
            'pedidos' => $pedidos,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }
    #[Route('/homepage/pedidos/{id}', name: 'pedido_detalle', methods: ['GET'])]
    public function showDetalle(CodigoPedido $codigoPedido, CarritoRepository $carritoRepository, PedidosRepository $pedidoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $pedidos = $pedidoRepository->findBy(['CodigoPedidoRelacion' => $codigoPedido->getId()]);

        $TotalPrecio = 0;
        foreach ($pedidos as $pedido) {
            if($pedido->getProducto()->getDescuento()){
                $TotalPrecio+= $pedido->getCantidad()*($pedido->getProducto()->getPrecio()*(1-($pedido->getProducto()->getDescuento()/100)));
            }else{
                $TotalPrecio+= $pedido->getCantidad()*$pedido->getProducto()->getPrecio();
            }
        }

        return $this->render('index/iniciado/pedido/pedidos.html.twig', [
            'pedidos' => $pedidos,
            'mostrarBoton' => $mostrarBoton,
            'TotalPrecio' => $TotalPrecio,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    
    #[Route('/homepage/carrito/cambiar/{id}', name: 'app_cambiar', methods: ['GET', 'POST'])]
    public function cambiar(Request $request, Carrito $carrito, CarritoRepository $carritoRepository, EntityManagerInterface $entityManager): Response
    {
        
        $user = $this->getUser();
        $mostrarBoton = false;

        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && in_array('ROLE_ADMIN', $user->getRoles())) {
            $mostrarBoton = true;
        }

        $form = $this->createForm(CarritoCambiar::class, $carrito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $cantidad = $data->getCantidad();
            $producto = $carrito->getProducto();

            if ($cantidad > $producto->getStock()) {
                $this->addFlash('error', 'No hay suficiente stock disponible.');
                return $this->redirectToRoute('carrito_online', [], Response::HTTP_SEE_OTHER);
            }else{
                $entityManager->flush();
                return $this->redirectToRoute('carrito_online', [], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('index/iniciado/carrito/edit.html.twig', [
            'carrito' => $carrito,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/homepage/carrito/comprar', name: 'app_comprar', methods: ['GET', 'POST'])]
    public function comprar(Request $request, EntityManagerInterface $entityManager, CarritoRepository $carritoRepository, CodigoPedidoRepository $codigoPedidoRepository): Response
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $codigoPedido = rand(1000, 9999);
        $imposible = false;
        
        $carritos = $carritoRepository->findBy(['Usuario' => $userId]);
        
        foreach ($carritos as $carrito) {
            $producto = $carrito->getProducto();
            if ($carrito->getCantidad() > $producto->getStock()) {
                $imposible = true;
                $producto_nombre = $producto->getNombre();
                break;
            }
        }
        
        if ($imposible) {
            $this->addFlash('error', 'No se puede completar la compra porque el producto "' . $producto_nombre . '" no tiene suficiente stock.');
            return $this->redirectToRoute('carrito_online');
        }

        $codigo_pedido = New CodigoPedido;
        $codigo_pedido->setCodigo($codigoPedido);
        $codigo_pedido->setCliente($user);
        $codigo_pedido->setFecha(new \DateTime('now'));
        $codigo_pedido->setEstado(0);
        $entityManager->persist($codigo_pedido);

        $entityManager->flush();

        $CodigoRelacion = $codigoPedidoRepository->findBy(['codigo' => $codigoPedido]);

        foreach ($carritos as $carrito) {
            $pedido = new Pedidos();
            $pedido->setEstado(0);
            $pedido->setCantidad($carrito->getCantidad());
            $pedido->setUsuario($carrito->getUsuario());
            $pedido->setProducto($carrito->getProducto());
            $producto = $carrito->getProducto();
            $producto->setStock($producto->getStock() - $carrito->getCantidad());
            $pedido->setCodigoPedido($codigoPedido);
            foreach ($CodigoRelacion as $codigo) {
                $pedido->setCodigoPedidoRelacion($codigo);
            }
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
    public function index_crud(ProductoRepository $productoRepository, CarritoRepository $carritoRepository): Response
    {
        
        $user = $this->getUser();
        $mostrarBotonAdmin = false;
        $mostrarBoton = false;

        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }


        if ($user && in_array('ROLE_ADMIN', $user->getRoles())) {
            $mostrarBotonAdmin = true;
        }

        return $this->render('index/iniciado/crud/index.html.twig', [
            'mostrarBotonAdmin' => $mostrarBotonAdmin,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }
}
