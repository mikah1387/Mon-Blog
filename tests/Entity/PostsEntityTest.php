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
                $post->setTitle('tes');
                $post->setSlug('tester');
                $post->setContent('');
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
         $messages =[];

         foreach ($errors as  $error) {
            $messages[]= $error->getPropertyPath() . ' => '. $error->getMessage();
         }
         $this->assertCount($number, $errors, implode(',',  $messages));
     }
    public function testValidEntity(): void
    {

        $this->assertHasErrors($this->getEntity(), 0);
    }
}
