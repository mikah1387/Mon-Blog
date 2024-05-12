<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostsFormType;
use App\Form\SearchPostType;
use App\Repository\CategoriesRepository;
use App\Repository\CommentsRepository;
use App\Repository\PostsRepository;
use App\Repository\UsersRepository;
use App\service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/articles', name: 'posts_')]

class PostsController extends AbstractController
{
 
    #[Route('/', name: 'index')]
    public function index( PostsRepository $postsRepo,Request $request, CacheInterface $cache ,PaginatorInterface $paginator,CategoriesRepository $categories, UsersRepository $autors ): Response
    {
      $posts = $cache->get('my_articles', function (ItemInterface $item) use ($postsRepo) {
        $item->expiresAfter(3600); 
        
          $datas = $postsRepo->findBy([],['Created_at'=>'DESC']);
 
        return $datas;
         });
         $page= $request->query->get('page',1);
        
         $paginations = $paginator->paginate($posts,$page,9);
         $categorie = $request->get('categorie');
         $trie =  $request->get('trie');
         if ($trie) {
          $postscat = $postsRepo->findPostsBycaty( $categorie,$trie);
          // dd($postscat);
          $paginations= $paginator->paginate($postscat,$page,9);     
         }
         if ($categorie) {
          $trie =  $request->get('trie');
          $postscat = $postsRepo->findPostsBycaty( $categorie,$trie);
          $paginations= $paginator->paginate($postscat,$page,2);     
         }
      
         if($request->get('ajax')){

          // $pag= $request->query->get('page',1);
         
          return new JsonResponse([
              
              'content'=> $this->renderView('posts/_content.html.twig',[
                             
            'paginations' =>$paginations,
               
                ])
            ]);
            }
        
         $mots= $request->request->all();   

          if ($request->isMethod('POST'))
          {
              
            return $this->redirectToRoute('posts_search',[
                     'mots' => $mots["search_post"]['mots']]);  
          }
        
        return $this->render('posts/index.html.twig',[
                             
                'paginations' => $paginations,
                'page'=>$page= $request->query->get('page',1),
                'categories'=>$categories->findby([],['name'=>'ASC']),
                'autors'=>$autors->findAll()
                // 'categorieId'=> null

        ]);
    }


   #[Route('/search', name: 'search')]
   public function search(Request $request, PostsRepository $postsRepo,PaginatorInterface $paginator)
   {
    $mots= $request->request->all();   

    if ($request->isMethod('POST'))
    {
        
      return $this->redirectToRoute('posts_search',[
               'mots' => $mots["search_post"]['mots']]);  
    }

        $mots = $request->query->get('mots');
        $posts = $postsRepo->searchTags($mots);
        $page= $request->query->get('page',1);

        $paginations= $paginator->paginate($posts,$page,1);
         return $this->render('posts/search.html.twig',[
          'paginations' => $paginations,
               
    ]);

   }


    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em,
    SluggerInterface $slugger, UserInterface $user,
    PictureService $pictureService, CacheInterface $cache ): Response
    {

          $this->denyAccessUnlessGranted('ROLE_USER');
       

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
             $cache->delete('my_articles');
             $cache->delete('user_'.$user->getNickname());
             $cache->delete('lastposts');
             $cache->delete('usersMoreActif');
             


             $this->addFlash('success', 'votre article '.$post->getTitle().' est bien ajouté');
                         return $this->redirectToRoute('posts_index');
         }
 
         return $this->render('posts/addpost.html.twig',['addpostform' => $form->createView(),
         'titre_form'=> 'Ajouter un article',
        'button_label'=> 'Ajouter l\'article']);
    }
 

    #[Route('/{slug}_{id}', name: 'detail', requirements:['id'=>Requirement::DIGITS])]
    public function detail(Posts $post, CommentsRepository $comments, CacheInterface $cache): Response
    {
          $article= $cache->get('post_'.$post->getSlug(), function(ItemInterface $item) use ($post){
            $item->expiresAfter(3600);
            return $post;
          });
        return $this->render('posts/detail.html.twig', [
            'post' => $article,

        ]);
    }
    #[Route('/update/{slug}_{id}', name: 'update',requirements:['id'=>Requirement::DIGITS])]
    public function update(Posts $post, Request $request, EntityManagerInterface $em,
    SluggerInterface $slugger, UserInterface $user, CacheInterface $cache,PictureService $pictureService ): Response
    {
     
      $this->denyAccessUnlessGranted('POST_UPDATE',$post);
          
          if($user === $post->getUsers() || $user->getRoles()[0]=== 'ROLE_ADMIN'){
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
                   $post->setFeaturedImage( $imageName );
                 
                $em->persist($post);
                $em->flush();
                $cache->delete('my_articles');
                $cache->delete('user_'.$user->getNickname());
                $cache->delete('lastposts');
                $cache->delete('usersMoreActif');


                $this->addFlash('success', 'votre article '.$post->getTitle().' est bien modifier');
                            return $this->redirectToRoute('profile_index');
                     
            }
    
            return $this->render('posts/addpost.html.twig',['addpostform' => $form->createView(),
           'button_label'=> 'Modifier l\'article',
           'titre_form'=> 'Modifier l\'article' ]);
          }else{
            $this->addFlash('alert', 'vous n\'avez pas le droit d\'acceder ici');
            return $this->redirectToRoute('profile_index');

          }
        
    }
   
    #[Route('/delete/{slug}_{id}', name: 'delete',requirements:['id'=>Requirement::DIGITS])]
    public function delete(Posts $post, EntityManagerInterface $em, UserInterface $user, CacheInterface $cache)
    {
            $this->denyAccessUnlessGranted('POST_DELETE', $post);
            if($user === $post->getUsers() || $user->getRoles()[0]=== 'ROLE_ADMIN'){

              $em->remove($post);
              $em->flush();
              $cache->delete('my_articles');
              $cache->delete('user_'.$user->getNickname());
              $cache->delete('lastposts');
              $cache->delete('usersMoreActif');
              $this->addFlash('success', 'l\'article '. $post->getTitle() .' est bien suprimer');
              return $this->redirectToRoute('profile_index');
            }else{

              $this->addFlash('alert', 'vous n\'avez pas le droit d\'acceder ici');
              return $this->redirectToRoute('profile_index');
  
            }
         

      

          
        

    }

  
}
