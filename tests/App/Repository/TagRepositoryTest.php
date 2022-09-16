<?php

namespace App\Tests\App\Repository;

use App\Entity\Tag;
use App\Repository\TagRepository;

class TagRepositoryTest extends BaseRepositoryTest
{
    public function testSearchByTerm(): void
    {
        $tagRepository = static::getContainer()->get(TagRepository::class);
        $this->createTags();
        $tags = $tagRepository->searchByTerm("random_0 ");
        $this->assertNotEmpty($tags);
        $this->assertEquals(1, count($tags));
    }

    private function createTags()
    {
        $tagRepository = static::getContainer()->get(TagRepository::class);

        for ($i = 0; $i < 10; $i++)
        {
            $tag = new Tag;
            $tag->setText($this->faker->bothify("random_$i ????"));
            $tagRepository->add($tag, true);
        }
    }
}
