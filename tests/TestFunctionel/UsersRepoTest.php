<?php

namespace App\Tests\TestFunctionel;

use App\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UsersRepoTest extends KernelTestCase
{
    public function testCount(): void
    {
        $kernel = self::bootKernel();

        $users = $kernel->getContainer()->get('doctrine')->getRepository(Users::class)->findAll();

        $this->assertCount(10, $users);

        // $this->assertSame('test', $kernel->getEnvironment());
        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }


}
