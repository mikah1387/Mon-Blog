<?php 

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UsersFixtures extends Fixture
{
    
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager)

    {
        
        
        for ($i=1; $i <=10 ; $i++) { 
            $user = new Users();

            $user->setNickname('user'.$i)
            ->setImage('https://randomuser.me/api/portraits/men/'.$i.'.jpg')
            ->setEmail('user'.$i.'@gmail.com')
            ->setPassword($this->passwordHasher->hashPassword(
                $user,
                'aaaaaa'
            ));
            $manager->persist($user);
        }

        $manager->flush();
    }
}