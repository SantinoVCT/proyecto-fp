<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccessDeniedHandler extends AbstractController implements AccessDeniedHandlerInterface 
{   
    public function handle(Request $request, AccessDeniedException $accessDeniedException): Response
    {
        // Lógica personalizada: puedes renderizar una plantilla, devolver un JSON, etc.
        //return new Response('¡Acceso Denegado! No tienes permisos suficientes.', 403);

        return $this->render('exception/error403.html.twig');

        // O renderizar una plantilla Twig si usas el AbstractController
        
    }
}

?>