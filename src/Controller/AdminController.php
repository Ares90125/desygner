<?php
namespace App\Controller;

use App\Entity\Image;
use App\Entity\Tag;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use App\Repository\TagRepository;
use App\Responses\ImageResponse;
use App\Validators\ImageCreateValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class AdminController extends AbstractController
{
    private ImageRepository $imageRepository;
    private TagRepository $tagRepository;

    public function __construct(
        ImageRepository $imageRepository,
        TagRepository $tagRepository
    )
    {
        $this->tagRepository = $tagRepository;
        $this->imageRepository = $imageRepository;
    }

    #[Route('/api/admin/images', name: "image_create", methods: ['POST'])]
    public function create(Request $request, SluggerInterface $slugger, ImageCreateValidator $validator): JsonResponse
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
        if ($imageFile) {
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

            try {
                $imageFile->move($this->getParameter('image_upload_directory'), $newFilename);
            } catch(FileException $ex) {
                return $this->json(['message' => $ex->getMessage()], 500);
            }

            $image->setUrl('/uploads/images/' . $newFilename);
        } else {
            $image->setUrl($data['url']);
        }
        if (!empty($data['tags'])) {
            foreach($data['tags'] as $tagText)
            {
                $tag = new Tag;
                $tag->setText($tagText);
                $this->tagRepository->add($tag);
                $image->addTag($tag);
            }
        }
        $this->imageRepository->add($image, true);
        return $this->json(ImageResponse::toArray($image));
    }

    public static function getFormErrorsTree($form): array
    {
        $errors = [];

        if (count($form->getErrors()) > 0) {
            foreach ($form->getErrors() as $error) {
                $errors[] = $error->getMessage();
            }
        } else {
            foreach ($form->all() as $child) {
                $childTree = self::getFormErrorsTree($child);

                if (count($childTree) > 0) {
                    $errors[$child->getName()] = $childTree;
                }
            }
        }

        return $errors;
    }

}
