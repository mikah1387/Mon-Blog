<?php

namespace App\Controller\admin;

use App\Entity\Categories;
use App\Form\CategoriesFormType;
use App\Repository\CategoriesRepository;
use App\service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin', name: 'admin_')]
class CategoriesController extends AbstractController
{
    #[Route('/categories/add', name: 'categorie_add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $caty = new Categories;
         
        $form = $this->createForm(CategoriesFormType::class,$caty);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
              
            $image = $form->get('image')->getData();
            $imageName = $pictureService->add($image,'categories',300,300);
               $slug= $slugger->slug($caty->getName());
               $caty->setSlug($slug);
               $caty->setImage($imageName);
             
            $em->persist($caty);
            $em->flush();
            $this->addFlash('success', 'la categorie '.$caty->getName().' est bien ajouté');
                        return $this->redirectToRoute('admin_index');
        }
        
        return $this->render('categories/addcaty.html.twig', ['addcatyform' => $form->createView(),
        'button_label'=> 'Ajouter'
         
        ]);
    }
   
  
}
