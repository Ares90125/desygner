<?php
namespace App\Controller\User;

use App\Entity\Image;
use App\Entity\User;
use App\Service\LibraryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{

    #[Route('/api/user/images/{image}/library', name: 'user_add_image_library', methods: ['PUT'], requirements: ['image' => '\d+'])]
    public function addLibrary(Image $image, LibraryService $libraryService): Response
    {
        /**
         * @var User $user
         */
        $user = $this->getUser();
        $libraryService->addToLibrary($user, $image);
        return $this->json([]);
    }

}
