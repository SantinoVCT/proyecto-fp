<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Form\CrearUsuario;
use App\Repository\UsuarioRepository;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        // $error = "Email y/o contraseña incorrectas intente de nuevo"; // $authenticationUtils->getLastAuthenticationError();
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('/register', name: 'app_registrar', methods: ['GET', 'POST'])]
    public function register(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, MailerInterface $mailer, UrlGeneratorInterface $urlGenerator): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(CrearUsuario::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // If password field was filled, hash and set it
            $plain = $form->get('password')->getData();
            if (!empty($plain)) {
                $usuario->setPassword($passwordHasher->hashPassword($usuario, $plain));
            }

            $usuario->setFechaCreada(new \DateTime());

            try {
                $usuario->setRoles(['ROLE_USER']);
                $usuario->setIsVerified(false);
                $usuario->setEmailVerificationToken(bin2hex(random_bytes(32)));

                $entityManager->persist($usuario);
                $entityManager->flush();

                $confirmationUrl = $urlGenerator->generate('app_verify_email', ['token' => $usuario->getEmailVerificationToken()], UrlGeneratorInterface::ABSOLUTE_URL);
                $fromAddress = getenv('MAILER_FROM') ?: 'no-reply@proyecto-fp.test';

                $email = (new TemplatedEmail())
                    ->from(new Address($fromAddress, 'Proyecto FP'))
                    ->to($usuario->getEmail())
                    ->subject('Confirma tu cuenta')
                    ->htmlTemplate('email/confirmation.html.twig')
                    ->context([
                        'usuario' => $usuario,
                        'confirmationUrl' => $confirmationUrl,
                    ]);

                $mailer->send($email);
            } catch (UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'El correo electrónico para el usuario ya está en uso.');
                return $this->redirectToRoute('app_registrar');
            }

            $this->addFlash('success', 'Tu cuenta se ha creado correctamente. Revisa tu correo para confirmar la cuenta.');

            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('security/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form,
        ]);
    }


    #[Route('/verify-email/{token}', name: 'app_verify_email')]
    public function verifyEmail(string $token, EntityManagerInterface $entityManager): Response
    {
        $usuario = $entityManager->getRepository(Usuario::class)->findOneBy(['emailVerificationToken' => $token]);

        if (!$usuario) {
            $this->addFlash('error', 'El enlace de confirmación no es válido.');

            return $this->redirectToRoute('app_login');
        }

        $usuario->setIsVerified(true);
        $usuario->setEmailVerificationToken(null);
        $entityManager->flush();

        $this->addFlash('success', 'Tu correo ha sido confirmado. Ya puedes iniciar sesión.');

        return $this->redirectToRoute('app_login');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
