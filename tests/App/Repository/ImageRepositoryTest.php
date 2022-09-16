<?php

namespace App\Tests\App\Repository;

use App\Entity\Image;
use App\Entity\Tag;
use App\Repository\ImageRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Faker\Factory;

class ImageRepositoryTest extends KernelTestCase
{
    protected $faker;
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->faker = Factory::create();
    }
    public function testGetWithTagSearchQueryBuilder(): void
    {
        $kernel = self::bootKernel();

        // $this->assertSame('test', $kernel->getEnvironment());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);

        $this->createImagesWithTags();

        $imageRepository = static::getContainer()->get(ImageRepository::class);

        $images = $imageRepository->findAll();

        $image1 = $images[0];
        $image2 = $images[1];

        $this->assertNotNull($image1);
        $this->assertNotNull($image2);

        $tags = $image1->getTags();
        $tag1 = $tags[0];

        $searchedImages = $imageRepository->getWithTagSearchQueryBuilder([$tag1->getId()])
            ->getQuery()
            ->getResult();
        $this->assertEquals(1, count($searchedImages));
        $this->assertEquals($image1->getId(), $searchedImages[0]->getId());

        $tags = $image2->getTags();
        $tag2 = $tags[0];
        $searchedImages = $imageRepository->getWithTagSearchQueryBuilder([$tag1->getId(), $tag2->getId()])
            ->getQuery()
            ->getResult();
        $searchedIds = \array_map(function ($item) { return $item->getId(); }, $searchedImages);
        $this->assertEquals(2, count($searchedImages));
        $this->assertContains($image1->getId(), $searchedIds);
        $this->assertContains($image2->getId(), $searchedIds);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    private function createImagesWithTags()
    {
        $imageRepository = static::getContainer()->get(ImageRepository::class);
        $tagRepository = static::getContainer()->get(TagRepository::class);

        for ($i = 0; $i < 10; $i++)
        {
            $tag = new Tag;
            $tag->setText($this->faker->bothify('?????'));
            $tagRepository->add($tag, true);
        }
        $tags = $tagRepository->findAll();
        foreach($tags as $tag)
        {
            $image = new Image;
            /**
             * currently faker->url() is buggy
             */
            // $image->setUrl($this->faker->url());
            $image->setUrl('https://test.com/test.png');
            $image->addTag($tag);
            $imageRepository->add($image, true);
        }
        $this->entityManager->flush();
    }
}
