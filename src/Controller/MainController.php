<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Repository\PostsRepository;
use App\Repository\UsersRepository;
use App\service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
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

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, SendMailService $mail)
    {

      $form = $this->createForm(ContactFormType::class);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid())
        {

        
        $name = $form->get('name')->getData();
        $subject = $form->get('subject')->getData();
        $content = $form->get('content')->getData();
        
        $email =  $form->get('email')->getData();
        $context =[
          'name'=>$name,
          'content'=>$content
        ];
        $mail->send(
          $email,
          'contact_us@sharearticle.ah-codeaddict.fr',
          $subject,
          'contact',$context
          );

          $this->addFlash('success', 'Email envoyé avec succés');
                return $this->redirectToRoute('main');
        }
        // else{
        //   $this->addFlash('alert','un probleme est survenu');
        //   return $this->redirectToRoute('main');
        // }
          
     return $this->render('contact/contact.html.twig',[
              'contactform' => $form->createView()
          ]);     
    }   
  
   
}
