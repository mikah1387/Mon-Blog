<?php

namespace App\DataFixtures;

use App\Entity\Comments;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use faker;
class CommentsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($c=1; $c <=10 ; $c++) 
           { 
               
        $comment = new Comments;
        $user = $this->getReference('user-'.rand(1,9));
        $post =$this->getReference('post-'.rand(1,9));
        $comment->setParent(null)
                ->setUsers($user)
                ->setPosts($post)
                ->setContent($faker->text(20))
                ->setIsReply(1);

              $manager->persist($comment);
             
           }

        $manager->flush();
    }
    public function getDependencies():array
    {
        return [
            UsersFixtures::class,
            PostsFixtures::class
        ];
    }
}
