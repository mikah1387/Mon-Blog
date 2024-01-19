<?php

namespace App\DataFixtures;

use App\Entity\Posts;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostsFixtures extends Fixture implements DependentFixtureInterface
{
      private $count =0;

    public function __construct( private SluggerInterface $slugger)
    {
        
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($p=1; $p <=10 ; $p++) 
        { 
            
          $post = new Posts;
          $user = $this->getReference('user-'.rand(1,8));
          $category = $this->getReference('cat-'.rand(0,9));
          $post->setTitle($faker->text(5))
                ->setSlug($this->slugger->slug($post->getTitle())->lower())
                ->setContent($faker->text())
                ->setFeaturedImage('url')
                ->setUsers($user)
                ->addCategory($category);

                $this->addReference('post-'.$this->count, $post);
                $this->count++; 


           $manager->persist($post);
          
        }



        $manager->flush();
        

       
    }
    public function getDependencies():array
    {
        return [
            UsersFixtures::class
        ];
    }
}
