<?php

namespace App\Controller;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/profile', name: 'profile_')]
class PorfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index( UserInterface $userInter , CacheInterface $cache): Response
    {
           $user = $cache->get('user_'.$userInter->getNickname(), function(ItemInterface $item )use($userInter){
               $item->expiresAfter(3600); 
               return  $userInter;
           });  

        //    dd($user);
        return $this->render('porfile/index.html.twig', [
            'user' => $user,
        ]);
    }
}
