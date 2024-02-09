<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Form\CategoriesFormType;
use App\Repository\CategoriesRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/categories', name: 'categories_')]
class CategoriesController extends AbstractController
{
   #[Route('/', name: 'index')]
    public function index(CategoriesRepository $categoriesRepo): Response
    {
        return $this->render('categories/index.html.twig', [
            'allcatygories' => $categoriesRepo-> findAll(),
        ]);
    }

    
    #[Route('/{slug}', name: 'postsbycaty')]
    public function postsbycaty( Categories $category): Response
    {
        return $this->render('categories/postsbycaty.html.twig', [
            'category' => $category
        ]);
    }

  
}
