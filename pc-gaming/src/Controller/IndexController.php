<?php

namespace App\Controller;

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
        return $this->render('index/iniciado/index.html.twig', [
            'user' => $this->getUser(),
            'productos' => $productoRepository->findAll(),
        ]);
    }
}
