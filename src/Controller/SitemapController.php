<?php

namespace App\Controller;

use App\Repository\CategoriesRepository;
use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SitemapController extends AbstractController
{
    #[Route('/sitemap', name: 'sitemap',defaults:['format'=>'XML'])]
    public function index(Request $request,
     PostsRepository $posts, CategoriesRepository $categories ): Response
    {
        $hostName = $request->getSchemeAndHttpHost();
        $urls[] = ['loc'=>$this->generateUrl('main')];
        $urls[] = ['loc'=>$this->generateUrl('posts_index')];
        $urls[] = ['loc'=>$this->generateUrl('contact')];
        $urls[] = ['loc'=>$this->generateUrl('posts_search')];
        $urls[] = ['loc'=>$this->generateUrl('profile_index')];

       foreach ($posts->findAll() as $post) {
        $urls[] = [
            'loc'=>$this->generateUrl('posts_detail', ['slug' => $post->getSlug(), 'id'=>$post->getId()]),
            'lastmod'=> $post->getCreatedAt()->format('Y-m-d')
                ];

       }

       foreach ( $categories->findCategoriesWithParentNotNull() as $caty) {
        
        $urls[] = ['loc'=>$this->generateUrl('categories_postsbycaty', ['slug' =>$caty->getSlug()])];

       }

        $response = new Response(

            $this->renderView('sitemap/index.html.twig', [
                'urls'=> $urls,
                'hostName'=>$hostName
            ]),200
            );
        
        $response->headers->set('Content-type','text/xml');   
       
        return $response;
    
    }
}
