<?php

namespace App\Controller;

use App\Form\ConfirmResetPassFormType;
use App\Form\ResetPasswordFormType;
use App\Repository\UsersRepository;
use App\service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/forgot_pass', name: 'forgot')]

    public function forgottenPassword(Request $request,UsersRepository $usersRepository,
    SendMailService $mail,EntityManagerInterface $em,
    TokenGeneratorInterface $tokenGeneratorInterface)
    {

        $form = $this->createForm(ResetPasswordFormType::class);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
        //     // on cherche l'utilisateur
            $user= $usersRepository->findOneByEmail($form->get('email')->getData());
            // dd($user);
            if ($user){
               
              $token = $tokenGeneratorInterface->generateToken();  
              $user->setResetToken($token);
              $em->persist($user);

              $em->flush();
            //   on envoie un mail de réinitialisation
                $url = $this->generateUrl('verifyreset',['token'=>$token],
                 UrlGeneratorInterface::ABSOLUTE_URL);
                $context = [
                'user'=>$user,
                'url'=>$url
                   ];
                 $mail->send(
                'contact@sharearticle.ah-codeaddict.fr',
                $user->getEmail(),
                'Réinitialisation de mot de passe',
                'resetpass',$context
               
            
                );
                $this->addFlash('success', 'Email envoyé avec succés');
                return $this->redirectToRoute('login');
            }
            $this->addFlash('alert','un probleme est survenu');
            return $this->redirectToRoute('login');
            
       

        }
    
        return $this->render('security/rest_pass.html.twig',[
            'resetPassword' => $form->createView()
        ]);
    }


    #[Route('/verifyreset/{token}', name: 'verifyreset')]
    public function verify($token,
     Request $request,
     UsersRepository $usersRepository,
     EntityManagerInterface $em,
     UserPasswordHasherInterface $passwordHasher )
    
    {
         $user= $usersRepository->findOneByresetToken($token);
   
         if($user){

                 $form = $this->createForm(ConfirmResetPassFormType::class);
                 $form->handleRequest($request);

              if($form->isSubmitted() && $form->isValid()){
               
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                    );
                    $em->persist($user);
                    $em->flush();

                    $this->addFlash('success','mot de passe modifier');
                    return  $this->redirectToRoute('login');
              }
                   

                 return $this->render('security/confirm_pass.html.twig',[
                            'resetPassword' => $form->createView()
                        ]);
         }else {
            $this->addFlash('alert','token invalide');
            return  $this->redirectToRoute('login');
          }
       
        
    
    }





    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
