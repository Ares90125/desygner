<?php
namespace App\Controller\User;

use App\Entity\Image;
use App\Entity\User;
use App\Repository\LibraryImageRepository;
use App\Responses\ImageResponse;
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

    #[Route('/api/user/library/images', name: 'user_library_image_list', methods: ['GET'])]
    public function getLibraryImages(LibraryImageRepository $libraryImageRepository)
    {
        $user = $this->getUser();
        $images = $libraryImageRepository->getLibraryImages($user);
        return $this->json(ImageResponse::toArray($images));
    }
}
