<?php

namespace App\Tests\Entity;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UsersEntityTest extends KernelTestCase    
{
    public function testValidEntity(): void
    {
    
        $user = new Users();
        $user->setNickname('hell');
        $user->setEmail('test@gmail');
        $user->setPassword('test'); 
        $kernel = self::bootKernel();
        $container = static::getContainer();
        $errors = $container->get('validator')->validate($user);
        $this->assertCount(3, $errors);
    }    
}