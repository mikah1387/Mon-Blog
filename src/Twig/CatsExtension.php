<?php
namespace App\Twig;

use App\Entity\Categories;
use App\Repository\CategoriesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormFactoryInterface;
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
        $categorie = $this->em->getRepository(Categories::class)->findBy([],['name'=>'ASC']);
        return $categorie;
    }
}