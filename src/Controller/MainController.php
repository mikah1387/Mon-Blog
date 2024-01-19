<?php

namespace App\Controller;

use App\Repository\PostsRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index( UsersRepository $usersRepository, PostsRepository $postsRepository): Response
    {
        
        return $this->render('main/index.html.twig', [
            'lastUserSubscrib' => $usersRepository->findBy([], ['id'=>'DESC'],1),
            'lastposts'=> $postsRepository->findBy([],['id'=>'DESC'],6),
            'usersactif'=> $postsRepository->findUsersActif()
        ]);
    }
  
   
}
