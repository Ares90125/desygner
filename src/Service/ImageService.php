<?php

namespace App\Service;

use App\Repository\ImageRepository;
use App\Repository\TagRepository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageService
{
    const UPLOADED_IMAGE_URL_PREFIX = '/uploads/images/';

    public function __construct(
        private ParameterBagInterface $params,
        private SluggerInterface $slugger,
        private ImageRepository $imageRepository,
        private TagRepository $tagRepository
    ) {}

    public function saveImageFromUrl(string $url): string
    {
        $file = \file_get_contents($url);
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        $filename = pathinfo($url, PATHINFO_FILENAME);

        $ext = $ext ?: 'jpg';
        $filename = 'c-' . $filename . '-' . uniqid() . '.' . $ext;
        \file_put_contents($this->params->get('image_upload_directory') . '/' . $filename, $file);
        return self::UPLOADED_IMAGE_URL_PREFIX . $filename;
    }

    public function saveImageFromBlob(mixed $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move($this->params->get('image_upload_directory'), $newFilename);
        return self::UPLOADED_IMAGE_URL_PREFIX . $newFilename;
    }


    public function searchImage($q, $page, $size): array
    {
        $tags = $this->tagRepository->searchByTerm($q);
        $tagIds = \array_map(function($item) { return $item->getId(); }, $tags );

        $imageQb = $this->imageRepository->getWithTagSearchQueryBuilder($tagIds);
        if ($page < 1) $page = 1;
        if ($size < 1) $size = 20;
        $offset = ($page - 1) * $size;
        return $imageQb->setMaxResults($size)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}
