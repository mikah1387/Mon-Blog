<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;

class SecuriteControllerTest extends WebTestCase
{
    public function testLoginPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('h1', 'Se connecter');
    }

    public function testLoginBadCredentials(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
         $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'achache.hakim@gmail.com',
            'password' => 'admin',  
        ]);

        $client->submit($form);
        $this->assertResponseRedirects('/login') ;        
        $client->followRedirect();
        $this->assertSelectorExists('.alert-danger');         
        $this->assertSelectorTextContains('.alert-danger', 'Identifiants invalides.');         
        // $this->assertResponseIsSuccessful();
        // $this->assertResponseStatusCodeSame(200);
        // $this->assertSelectorTextContains('h1', 'Se connecter');
    }

    public function testSuccessFullLogin(): void
    {
         $client = static::createClient();
        $crawler = $client->request('GET', '/login');
         $form = $crawler->selectButton('Se connecter')->form([
            'email' => 'user1@gmail.com',
            'password' => 'aaaaaa',  
        ]);

        $client->submit($form);
        // $this->assertResponseRedirects('/profile') ;        
       
        $client->followRedirect();
     
        $this->assertSelectorExists('h2');         
         $this->assertSelectorTextContains('h2 span', 'user1');         

        $this->assertResponseStatusCodeSame(200);

        // $this->assertResponseIsSuccessful();
    }

}
