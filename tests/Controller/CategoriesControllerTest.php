<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoriesControllerTest extends WebTestCase
{
    public function testCatyPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/categories/');

    

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorExists('.categories');
        // $this->assertAnySelectorTextContains('h2','intelligence-artificielle');
        
    }
}
