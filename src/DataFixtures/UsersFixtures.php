<?php 

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture
{
    
    public function load(ObjectManager $manager)

    {
        
        for ($i=1; $i <=10 ; $i++) { 
            $user = new Users();

            $user->setNickname('user'.$i)
            ->setImage('https://randomuser.me/api/portraits/men/'.$i.'.jpg')
            ->setEmail('user'.$i.'@gmail.com')
            ->setPassword('aaaaaa');
            $manager->persist($user);
        }

        $manager->flush();
    }
}