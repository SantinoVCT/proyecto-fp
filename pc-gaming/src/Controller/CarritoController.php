<?php

namespace App\Controller;

use App\Entity\Carrito;
use App\Form\CarritoForm;
use App\Repository\CarritoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/carrito')]
final class CarritoController extends AbstractController
{
    #[Route(name: 'app_carrito_index', methods: ['GET'])]
    public function index(CarritoRepository $carritoRepository): Response
    {
        return $this->render('carrito/index.html.twig', [
            'carritos' => $carritoRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_carrito_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $carrito = new Carrito();
        $form = $this->createForm(CarritoForm::class, $carrito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($carrito);
            $entityManager->flush();

            return $this->redirectToRoute('app_carrito_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('carrito/new.html.twig', [
            'carrito' => $carrito,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carrito_show', methods: ['GET'])]
    public function show(Carrito $carrito): Response
    {
        return $this->render('carrito/show.html.twig', [
            'carrito' => $carrito,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_carrito_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Carrito $carrito, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarritoForm::class, $carrito);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_carrito_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('carrito/edit.html.twig', [
            'carrito' => $carrito,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_carrito_delete', methods: ['POST'])]
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
}
