<?php

namespace App\Controller;

use App\Repository\PostsRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index( UsersRepository $usersRepository, PostsRepository $postsRepository,Request $request , CacheInterface $cache): Response
    {
          $lastUserSubscrib = $cache->get('lastUserSubscrib', function(ItemInterface $item) use($usersRepository) {
            $item->expiresAfter(3600); 
            return  $usersRepository->findBy([], ['id'=>'DESC'],1);
          });

          $lastposts = $cache->get('lastposts', function(ItemInterface $item) use($postsRepository) {
            $item->expiresAfter(3600); 
            return  $postsRepository->findBy([],['id'=>'DESC'],6);
          });
     
          $usersMoreActif = $cache->get('usersMoreActif', function(ItemInterface $item) use($postsRepository) {
            $item->expiresAfter(3600); 
            return  $postsRepository->findUsersActif();
          });
        
        $mots= $request->request->all();      
          if ($request->isMethod('POST')){
             
         return $this->redirectToRoute('posts_search',[
                     'mots' => $mots["search_post"]['mots'],

        ]);
             
           }
        
        return $this->render('main/index.html.twig', [
            'lastUserSubscrib' => $lastUserSubscrib,
            'lastposts'=> $lastposts,
            'usersactif'=> $usersMoreActif
        ]);
    }
  
   
}
