<?php

namespace App\Controller;

use App\Form\EditUsersFormType;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

#[Route('/profile', name: 'profile_')]
class PorfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index( UserInterface $userInter , CacheInterface $cache, Request $request,EntityManagerInterface $em): Response
    {
           $user = $cache->get('user_'.$userInter->getNickname(), function(ItemInterface $item )use($userInter){
               $item->expiresAfter(3600); 
               return  $userInter;
           });  
            
           $form = $this->createForm(EditUsersFormType::class,$user);
           $form->handleRequest($request);
           if ($form->isSubmitted() && $form->isValid()) {
           
            $em->persist($user);

            $em->flush();
            $this->addFlash('success', 'votre profil  est mis a jour');
            return $this->redirectToRoute('profile_index');

           }
  
        //    dd($user);
        return $this->render('porfile/index.html.twig', [
            'user' => $user,
            'editprofil'=> $form->createView()
        ]);
    }

    // #[Route('/Editer', name: 'update')]
    // public function upadate(UserInterface $userInter,Request $request,EntityManagerInterface $entityManager)
    // {


    // }

}
