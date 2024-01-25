<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
