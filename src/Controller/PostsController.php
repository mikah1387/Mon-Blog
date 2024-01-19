<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostsFormType;
use App\Repository\CommentsRepository;
use App\Repository\PostsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/articles', name: 'posts_')]

class PostsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index( PostsRepository $postsRepository): Response
    {
        return $this->render('posts/index.html.twig', [
            'articles' => $postsRepository->findAll(),
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em,
    SluggerInterface $slugger, UserInterface $user ): Response
    {

        
         $post = new Posts;
         
         $form = $this->createForm(PostsFormType::class,$post);
         $form->handleRequest($request);
         
         if ($form->isSubmitted() && $form->isValid()) {
           
              $slug= $slugger->slug($post->getTitle());
                $post->setSlug($slug);
                $post->setUsers($user);
                $post->setFeaturedImage('url');
              
             $em->persist($post);
             $em->flush();
             $this->addFlash('success', 'votre article '.$post->getTitle().' est bien ajouté');
                         return $this->redirectToRoute('posts_index');
         }
 
         return $this->render('posts/addpost.html.twig',['addpostform' => $form->createView()]);
    }

    #[Route('/{slug}', name: 'detail')]
    public function detail(Posts $post, CommentsRepository $comments): Response
    {
        return $this->render('posts/detail.html.twig', [
            'post' => $post,
            'comments'=>$comments->findAll()
        ]);
    }

   
  
}
