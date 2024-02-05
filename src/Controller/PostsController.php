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
                $categories = $form->get('categories')->getData();
             
                $slug= $slugger->slug($post->getTitle());
                $post->setSlug($slug);
                $post->setUsers($user);
                foreach ($categories as  $categorie) {
                $post->addCategory($categorie);
                }
                $post->setFeaturedImage('url');
              
             $em->persist($post);
             $em->flush();
             $this->addFlash('success', 'votre article '.$post->getTitle().' est bien ajouté');
                         return $this->redirectToRoute('posts_index');
         }
 
         return $this->render('posts/addpost.html.twig',['addpostform' => $form->createView(),
        'button_label'=> 'Ajouter l\'article']);
    }

    #[Route('/{slug}', name: 'detail')]
    public function detail(Posts $post, CommentsRepository $comments): Response
    {
        return $this->render('posts/detail.html.twig', [
            'post' => $post,
            'allcomments'=>$comments->findBy(['posts'=>$post],['id'=>'DESC'])
        ]);
    }
    #[Route('/update/{slug}', name: 'update')]
    public function update(Posts $post, Request $request, EntityManagerInterface $em,
    SluggerInterface $slugger, UserInterface $user ): Response
    {

         
         $form = $this->createForm(PostsFormType::class,$post);
         $form->handleRequest($request);
         
         if ($form->isSubmitted() && $form->isValid()) {
                $categories = $form->get('categories')->getData();
             
                $slug= $slugger->slug($post->getTitle());
                $post->setSlug($slug);
                $post->setUsers($user);
                foreach ($categories as  $categorie) {
                $post->addCategory($categorie);
                }
                $post->setFeaturedImage('url');
              
             $em->persist($post);
             $em->flush();
             $this->addFlash('success', 'votre article '.$post->getTitle().' est bien modifier');
                         return $this->redirectToRoute('profile_index');
         }
 
         return $this->render('posts/updatepost.html.twig',['updateform' => $form->createView(),
        'button_label'=> 'Modifier l\'article',
        'titre_form'=> 'Modifier l\'article' ]);
    }
   
    #[Route('/delete/{slug}', name: 'delete')]
    public function delete(Posts $post, EntityManagerInterface $em)
    {
        $em->remove($post);
        $em->flush();
        $this->addFlash('success', 'l\'article '. $post->getTitle() .' est bien suprimer');
        return $this->redirectToRoute('profile_index');

    }

  
}
