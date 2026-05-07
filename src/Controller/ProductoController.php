<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Form\ProductoForm;
use App\Repository\ProductoRepository;

use App\Repository\CarritoRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/homepage/DB/producto')]
final class ProductoController extends AbstractController
{
    #[Route(name: 'app_producto_index', methods: ['GET'])]
    public function index(ProductoRepository $productoRepository, CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('producto/index.html.twig', [
            'productos' => $productoRepository->findAll(),
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/new', name: 'app_producto_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $producto = new Producto();
        $form = $this->createForm(ProductoForm::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $producto->setFechaCreada(new \DateTime());

            $imageFile = $form->get('imagen')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    $producto->setImagen($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error al subir la imagen.');
                }
            }

            $entityManager->persist($producto);
            $entityManager->flush();

            return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('producto/new.html.twig', [
            'producto' => $producto,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/{id}', name: 'app_producto_show', methods: ['GET'])]
    public function show(Producto $producto, CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('producto/show.html.twig', [
            'producto' => $producto,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_producto_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Producto $producto, EntityManagerInterface $entityManager, SluggerInterface $slugger, CarritoRepository $carritoRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;
        $idUser = $user->getId();

        $numero_carro = count($carritoRepository->findBy(['Usuario' => $idUser]));

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $form = $this->createForm(ProductoForm::class, $producto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $producto->setFechaUpdate(new \DateTime());
            
            $imageFile = $form->get('imagen')->getData();
            
            if ($imageFile) {
                if($producto->getImagen()) {
                    $existingImagePath = $this->getParameter('images_directory').'/'.$producto->getImagen();
                    if (file_exists($existingImagePath)) {
                        unlink($existingImagePath);
                    }
                }

                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                    $producto->setImagen($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error al subir la imagen.');
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('producto/edit.html.twig', [
            'producto' => $producto,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
            'carro_num' => $numero_carro,
            'carritos' => $carritoRepository->findBy(['Usuario' => $idUser]),
        ]);
    }

    #[Route('/{id}', name: 'app_producto_delete', methods: ['POST'])]
    public function delete(Request $request, Producto $producto, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$producto->getId(), $request->getPayload()->getString('_token'))) {
            try {
                if($producto->getImagen()) {
                    $existingImagePath = $this->getParameter('images_directory').'/'.$producto->getImagen();
                    if (file_exists($existingImagePath)) {
                        unlink($existingImagePath);
                    }
                }
                $entityManager->remove($producto);
                $entityManager->flush();
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash('error', 'No se puede borrar el producto porque está relacionado con otros registros.');
                return $this->redirectToRoute('app_producto_index');
            }
        }
        return $this->redirectToRoute('app_producto_index', [], Response::HTTP_SEE_OTHER);
    }
}
