<?php
namespace App\Controller;

use App\Responses\UserResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeController extends AbstractController
{
    #[Route('/api/me', name: 'api_me')]
    public function me(): Response
    {
        $user = $this->getUser();
        return $this->json([
            'user' => UserResponse::toArray($user)
        ]);
    }
}
