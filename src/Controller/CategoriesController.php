<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesFormType;
use App\Repository\CategoriesRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
   #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepo): Response
    {
        return $this->render('categories/index.html.twig', [
            'allcatygories' => $categoriesRepo-> findAll(),
        ]);
    }

    
    #[Route('/{slug}', name: 'postsbycaty')]
    public function postsbycaty( Categories $category, CacheInterface $cache, UsersRepository $userRepository ): Response
    {
          $categorie = $cache->get('category_'.$category->getSlug(), function(ItemInterface $item) use($category, $userRepository){
            $item->expiresAfter(3600);
             $posts = $category->getPosts();
             foreach ($posts as $post) {
                $user = $userRepository->find($post->getUsers());           
            
                $post->setUsers($user);
                $post->addCategory($category);

             }
              return $category;
          });
        return $this->render('categories/postsbycaty.html.twig', [
            'category' => $categorie
        ]);
    }

  
}
