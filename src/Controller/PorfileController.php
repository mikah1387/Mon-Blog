<?php

namespace App\Controller;

use App\Form\EditPassWordFormType;
use App\Form\EditUsersFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/profile', name: 'profile_')]
class PorfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index( UserInterface $userInter , CacheInterface $cache, Request $request,EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher, UsersRepository $userRepo): Response
    {
           $user = $cache->get('user_'.$userInter->getNickname(), function(ItemInterface $item )use($userInter){
               $item->expiresAfter(3600); 
               return  $userInter;
           });  
            
           $userEntity = $userRepo->find($user);
           
           $form = $this->createForm(EditUsersFormType::class, $userEntity);
           $form->handleRequest($request);
           if ($form->isSubmitted() && $form->isValid()) {
           
       
            $em->persist($userEntity);
            $em->flush();
            $cache->delete('post_'.$userInter->getNickname());

            $this->addFlash('success', 'votre profil  est mis a jour');
            return $this->redirectToRoute('profile_index');

           }
           $formpass = $this->createForm(EditPassWordFormType::class);
           $formpass->handleRequest($request);
         
           if ($formpass->isSubmitted() && $formpass->isValid()) {   
          $password = $formpass->get('password')->getData();
          $password_conf = $formpass->get('password_confirm')->getData();
         if ($password == $password_conf ) {
             
            $userEntity->setPassword(
                $userPasswordHasher->hashPassword(
                    $userEntity,$password
                    
                )
            );
       
            $em->persist($userEntity);
            $em->flush();
            $this->addFlash('success', 'votre profil  est mis a jour');
            return $this->redirectToRoute('profile_index');
             }else{
                $this->addFlash('alert', 'mots de passe ne sont pas identiques ');
                return $this->redirectToRoute('profile_index');

             }

            }
  
  
        //    dd($user);
        return $this->render('porfile/index.html.twig', [
            'user' => $user,
            'editprofil'=> $form,
            'editpassword'=> $formpass,
            
        ]);
    }

    // #[Route('/Editer', name: 'update')]
    // public function upadate(UserInterface $userInter,Request $request,EntityManagerInterface $entityManager)
    // {


    // }

}
