<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostsFormType;
use App\Form\SearchPostType;
use App\Repository\CommentsRepository;
use App\Repository\PostsRepository;
use App\Repository\UsersRepository;
use App\service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/articles', name: 'posts_')]

class PostsController extends AbstractController
{
 
    #[Route('/', name: 'index')]
    public function index( PostsRepository $postsRepo,Request $request , UsersRepository $userRepository ): Response
    {
    
       $cache = new FilesystemAdapter();
        $posts = $cache->get('my_articles', function (ItemInterface $item) use ($postsRepo,$userRepository) {
            $item->expiresAfter(60); 
            $datas = $postsRepo->findBy([],['Created_at'=>'DESC']);
            foreach ($datas as $post) {
               
                $user = $userRepository->find($post->getUsers());        
                $post->setUsers($user);
            }
      
               
            return $datas;
        });
       
        $data = $cache->getItem('my_articles');
     
        // $posts = $postsRepo->findBy([],['Created_at'=>'DESC']);
           $posts= $data->get();
        // $mots= $request->request->all();     
      
        //   if ($request->isMethod('POST')){
              
        //     return $this->redirectToRoute('posts_search',[
        //              'mots' => $mots["search_post"]['mots'],

        // ]);
             
        //    }
        
        return $this->render('posts/index.html.twig',[
               
                // 'searchtags' => $form,
                'articles' => $posts,

        ]);
    }
   #[Route('/search', name: 'search')]
   public function search(Request $request, PostsRepository $postsRepo){

         $mots = $request->query->get('mots');
        $posts = $postsRepo->searchTags($mots);
         return $this->render('posts/search.html.twig',[
            'posts'=>$posts
               
    ]);

   }
    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em,
    SluggerInterface $slugger, UserInterface $user,
    PictureService $pictureService ): Response
    {


         $post = new Posts;
         
         $form = $this->createForm(PostsFormType::class,$post);
         $form->handleRequest($request);
         
         if ($form->isSubmitted() && $form->isValid()) {
                $categories = $form->get('categories')->getData();
                $image = $form->get('image')->getData();
                $imageName = $pictureService->add($image,'posts',300,300);
             
                $slug= $slugger->slug($post->getTitle());
                $post->setSlug($slug);
                $post->setUsers($user);
                foreach ($categories as  $categorie) {
                $post->addCategory($categorie);
                }
                $post->setFeaturedImage($imageName);
              
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
        
          if($user === $post->getUsers() || in_array("ROLE_ADMIN", $user->getRoles())) {
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
          }else{
            $this->addFlash('alert', 'vous n\'avez pas le droit d\'acceder ici');
            return $this->redirectToRoute('profile_index');

          }
        
    }
   
    #[Route('/delete/{slug}', name: 'delete')]
    public function delete(Posts $post, EntityManagerInterface $em, UserInterface $user)
    {
        if($user === $post->getUsers() || in_array("ROLE_ADMIN", $user->getRoles())) {
            $em->remove($post);
            $em->flush();
            $this->addFlash('success', 'l\'article '. $post->getTitle() .' est bien suprimer');
            return $this->redirectToRoute('profile_index');

        }else{
            $this->addFlash('alert', 'vous n\'avez pas le droit d\'acceder ici');
            return $this->redirectToRoute('profile_index');

          }
        

    }

  
}
