<?php

namespace App\Controller\admin;


use App\Repository\CategoriesRepository;
use App\Repository\PostsRepository;
use App\Repository\UsersRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin', name: 'admin_')]
class MainController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index( UsersRepository $usersRepository, PostsRepository $postsRepository, CategoriesRepository $categoriesRepository): Response
    {
        
        return $this->render('admin/index.html.twig', [
            'lastUserSubscrib' => $usersRepository->findBy([], ['id'=>'ASC']),
            'allPosts'=>$postsRepository->findAll(),
            'allCategories'=> $categoriesRepository->findBy([],['name'=>'ASC'])       
             ]);
    }
  
   
}