<?php
namespace App\Controller\Admin;

use App\Entity\Image;
use App\Entity\Tag;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use App\Repository\TagRepository;
use App\Responses\ImageResponse;
use App\Service\ImageService;
use App\Validators\ImageCreateValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class AdminController extends AbstractController
{

    public function __construct(
        private ImageRepository $imageRepository,
        private TagRepository $tagRepository
    ) {}

    #[Route('/api/admin/images', name: "image_create", methods: ['POST'])]
    public function create(Request $request, ImageService $imageService, ImageCreateValidator $validator): JsonResponse
    {
        $user = $this->getUser();
        $result = $validator->validate($request);
        if ($result->count()) {
            return $this->json(['message' => (string)$result], 400);
        }

        $data = $request->request->all();

        $image = new Image();
        $image->setUser($user);
        $image->setProvider($data['provider']);

        $imageFile = $request->files->get('image');
        try {
            if ($imageFile) {
                $url = $imageService->saveImageFromBlob($imageFile);
                $image->setUrl($url);
            } else {
                $url = $imageService->saveImageFromUrl($data['url']);
                $image->setUrl($url);
            }
        } catch(FileException $ex) {
            return $this->json(['message' => 'Image upload failed'], 500);
        }
        if (!empty($data['tags'])) {
            foreach($data['tags'] as $tagText)
            {
                $tag = $this->tagRepository->findOneBy(['text' => $tagText]);
                if (!$tag) {
                    $tag = new Tag;
                    $tag->setText($tagText);
                    $this->tagRepository->add($tag);
                }
                $image->addTag($tag);
            }
        }
        $this->imageRepository->add($image, true);
        return $this->json(ImageResponse::toArray($image));
    }
}
