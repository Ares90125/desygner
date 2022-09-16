<?php
namespace App\Controller;

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
    public function list(Request $request)
    {
        $page = $request->query->get('page');
        $size = $request->query->get('size');
        $page = $page?: 1;
        $size = $size?: 20;


    }
}
