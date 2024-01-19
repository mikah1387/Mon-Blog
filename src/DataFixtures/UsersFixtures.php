<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;


class UsersFixtures extends Fixture
{
     private $count = 0;
    public function __construct( private UserPasswordHasherInterface $passwordEncoder, private SluggerInterface $slugger)
    {
        
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');

        $admin = new Users;
        $admin->setEmail('admin@admin.fr')
              ->setRoles(['ROLE_ADMIN'])
              ->setPassword(
               $this->passwordEncoder->hashPassword($admin, 'admin11'))
               ->setNickname('admin');

           $manager->persist($admin);

          

           for ($usr=1; $usr <=10 ; $usr++) 
           { 
               
        $user = new Users;
        $user->setEmail($faker->email)
              ->setRoles(['ROLE_USER'])
              ->setPassword(
              $this->passwordEncoder->hashPassword($user, '123456'))
              ->setNickname($faker->lastName);
              $this->addReference('user-'.$this->count, $user);
               $this->count++;   
              $manager->persist($user);
             
           }



           $manager->flush();
    }
}
