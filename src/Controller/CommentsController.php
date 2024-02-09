<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Form\CommentsFormType;
use App\Repository\CommentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/comments', name: 'comments_')]

class CommentsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index( CommentsRepository $commentsRepo): Response
    {
        return $this->render('comments/index.html.twig', [
            'comments' => $commentsRepo->findAll(),
        ]);
    }

    #[Route('/ajouter/{id}', name: 'add')]
    public function add( Request $request, EntityManagerInterface $em,
    UserInterface $user,Posts $post): Response
    {
         
         $comment = new Comments;
         $form = $this->createForm(CommentsFormType::class, $comment);
         $form->handleRequest($request);
         
         if ($form->isSubmitted() && $form->isValid()) {
           
                $comment->setUsers($user);
                $comment->setIsReply(true);
                $comment->setParent(null);
                $comment->setPosts($post);
                
              
             $em->persist($comment);
             $em->flush();
             $this->addFlash('success', 'votre conmmentaire est bien ajouté ');
                         return $this->redirectToRoute('posts_detail',['slug'=>$post->getSlug()]);
         }
 
         return $this->render('comments/addcomment.html.twig',['addcommentform' => $form->createView(),
        'button_label'=> 'Commenter',
        'title'=> 'Ajouter votre commentaire']);
      
    }

    #[Route('/reponse/{id}', name: 'add_response')]
    public function addResponse( Request $request, EntityManagerInterface $em,
    UserInterface $user,Comments $comment): Response
    {
         $post=$comment->getPosts();
         $response = new Comments;
         $form = $this->createForm(CommentsFormType::class, $response);
         $form->handleRequest($request);
         
         if ($form->isSubmitted() && $form->isValid()) {
           
                $response->setUsers($user);
                $response->setIsReply(true);
                $response->setParent($comment);
                $response->setPosts($post);
                
              
             $em->persist($response);
             $em->flush();
             $this->addFlash('success', 'votre réponse est bien ajouté ');
                         return $this->redirectToRoute('posts_detail',['slug'=>$post->getSlug()]);
         }
 
         return $this->render('comments/addcomment.html.twig',['addcommentform' => $form->createView(),
        'button_label'=> 'Répondez',
        'title'=> 'Ajouter votre réponse']);
      
    }

    #[Route('/update/{id}', name: 'update')]
    public function updateComment( Request $request, EntityManagerInterface $em,
    UserInterface $user,Comments $comment): Response
    {
        //  $post=$comment->getPosts();
         
         $form = $this->createForm(CommentsFormType::class, $comment);
         $form->handleRequest($request);
         
         if ($form->isSubmitted() && $form->isValid()) {
             
              
             $em->persist($comment);
             $em->flush();
             $this->addFlash('success', 'votre commentaire est bien modifié ');
                         return $this->redirectToRoute('profile_index');
         }
 
         return $this->render('comments/addcomment.html.twig',['addcommentform' => $form->createView(),
        'button_label'=> 'modifier',
        'title'=> 'Modifier votre commentaire']);
      
    }
    #[Route('/delete/{id}', name: 'delete')]
    public function delete( Comments $comment,EntityManagerInterface $em )
    {

        $em->remove($comment);
        $em->flush();
        $this->addFlash('success', 'votre commentaire est bien suprimé ');
                    return $this->redirectToRoute('profile_index'); 
    }

}
