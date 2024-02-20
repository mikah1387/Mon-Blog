<?php
namespace App\Twig;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Cache\FilesystemCache;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CatsExtension extends AbstractExtension
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('cats', [$this, 'getCategories'])
        ];
    }

    public function getCategories ()
    {
         $cache = new FilesystemAdapter();
         $categories = $cache->get('categorie', function(){

            return $this->em->getRepository(Categories::class)->findBy([],['name'=>'ASC']);
         });
        // $categories =  $this->em->getRepository(Categories::class)->findBy([],['name'=>'ASC']); 
        return $categories;
    }
}