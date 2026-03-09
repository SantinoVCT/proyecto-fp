<?php

namespace App\Controller;

use App\Entity\Anuncio;
use App\Form\AnuncioForm;
use App\Repository\AnuncioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/homepage/DB/anuncio')]
final class AnuncioController extends AbstractController
{
    #[Route(name: 'app_anuncio_index', methods: ['GET'])]
    public function index(AnuncioRepository $anuncioRepository): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('anuncio/index.html.twig', [
            'anuncios' => $anuncioRepository->findAll(),
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/new', name: 'app_anuncio_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        $anuncio = new Anuncio();
        $form = $this->createForm(AnuncioForm::class, $anuncio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
                    $anuncio->setImagen($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error al subir la imagen.');
                }
            }

            $entityManager->persist($anuncio);
            $entityManager->flush();

            return $this->redirectToRoute('app_anuncio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('anuncio/new.html.twig', [
            'anuncio' => $anuncio,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/{id}', name: 'app_anuncio_show', methods: ['GET'])]
    public function show(Anuncio $anuncio): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }

        return $this->render('anuncio/show.html.twig', [
            'anuncio' => $anuncio,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_anuncio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Anuncio $anuncio, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        $mostrarBoton = false;

        if ($user && (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_GESTOR', $user->getRoles()))) {
            $mostrarBoton = true;
        }


        $form = $this->createForm(AnuncioForm::class, $anuncio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $imageFile = $form->get('imagen')->getData();
            
            if ($imageFile) {
                if($anuncio->getImagen()) {
                    $existingImagePath = $this->getParameter('images_directory').'/'.$anuncio->getImagen();
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
                    $anuncio->setImagen($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Error al subir la imagen.');
                }
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_anuncio_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('anuncio/edit.html.twig', [
            'anuncio' => $anuncio,
            'form' => $form,
            'mostrarBoton' => $mostrarBoton,
        ]);
    }

    #[Route('/{id}', name: 'app_anuncio_delete', methods: ['POST'])]
    public function delete(Request $request, Anuncio $anuncio, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$anuncio->getId(), $request->getPayload()->getString('_token'))) {
            try {
                $entityManager->remove($anuncio);
                $entityManager->flush();
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash('error', 'No se puede borrar el anuncio porque está relacionado con otros registros.');
                return $this->redirectToRoute('app_anuncio_index');
            }
        }

        return $this->redirectToRoute('app_anuncio_index', [], Response::HTTP_SEE_OTHER);
    }
}
