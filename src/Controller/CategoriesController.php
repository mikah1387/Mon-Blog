<?php

namespace App\Controller;

use App\Entity\Categories;

use App\Repository\CategoriesRepository;
use App\Repository\PostsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{

    #[Route('/{slug}', name: 'postsbycaty')]
    public function postsbycaty( Categories $category, CacheInterface $cache, Request $request,PaginatorInterface $paginat,PostsRepository $potsrepo): Response
    {

          $posts = $cache->get('posts_of_'.$category->getSlug(), function(ItemInterface $item) use($potsrepo,$category){
            $item->expiresAfter(3600);
              return $potsrepo->findPostsBycaty($category);
          });
          
          $page= $request->query->get('page',1);      
         $paginations = $paginat->paginate(  $posts,$page,2);
          
        return $this->render('categories/postsbycaty.html.twig', [
            'paginations' => $paginations
        ]);
    }



   #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepo): Response
    {
        return $this->render('categories/index.html.twig', [
            'allcatygories' => $categoriesRepo-> findAll(),
        ]);
    }

    

  
}
