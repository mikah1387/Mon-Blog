<?php

namespace App\Tests\Entity;

use App\Entity\Categories;
use App\Entity\Posts;
use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostsEntityTest extends KernelTestCase
{

     public function getEntity(): Posts
     {
                $post = new Posts();
                $post->setTitle('tesrt');
                $post->setSlug('tester');
                $post->setContent('test content');
                $post->setFeaturedImage('test image');
                $post->setUsers(new Users());                       
                $post->setCreatedAt(new \DateTimeImmutable());
                return $post;
      
       
     }

     public function assertHasErrors(Posts $post, int $number = 0)
     {
        $kernel = self::bootKernel();
        $container = static::getContainer();
         $errors = $container->get('validator')->validate($post);
         $this->assertCount($number, $errors);
     }
    public function testValidEntity(): void
    {

        $this->assertHasErrors($this->getEntity(), 0);
    }
}
