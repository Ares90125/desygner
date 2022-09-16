<?php
namespace App\Controller;

use App\Entity\Image;
use App\Responses\ImageDetailResponse;
use App\Responses\ImageResponse;
use App\Responses\UserResponse;
use App\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ImageController extends AbstractController
{
    public function __construct(
        private ImageService $imageService
    ) {}

    #[Route('/api/images', name: "image_list", methods: ['GET'])]
    public function list(Request $request): Response
    {
        $page = $request->query->get('page');
        $size = $request->query->get('size');
        $q = $request->query->get('q');
        $page = $page?: 1;
        $size = $size?: 20;

        $images = $this->imageService->searchImage($q, $page, $size);

        return $this->json(ImageResponse::toArray($images));
    }

    #[Route('/api/images/{image}', name: 'image_retrieve', methods: ['GET'], requirements: ['image' => '\d+'])]
    public function retrieve(Image $image): Response
    {
        return $this->json(ImageDetailResponse::toArray($image));
    }
}
