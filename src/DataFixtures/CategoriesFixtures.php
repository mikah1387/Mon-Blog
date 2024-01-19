<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriesFixtures extends Fixture
{   
     private $count = 0;
    public function  __construct( private SluggerInterface $slugger)
    {
        
    }
    public function load(ObjectManager $manager): void
    {
         $parent1 = $this->CreateCategory('sport', null, $manager);
         $this->CreateCategory('football', $parent1, $manager);
         $this->CreateCategory('natation', $parent1, $manager);
         $this->CreateCategory('musculation', $parent1, $manager);
         $this->CreateCategory('running', $parent1, $manager);
         $parent2 = $this->CreateCategory('developement-web', null, $manager);
         $this->CreateCategory('backend', $parent2, $manager);
         $this->CreateCategory('frontend', $parent2, $manager);
         $this->CreateCategory('docker', $parent2, $manager);
         $this->CreateCategory('git', $parent2, $manager);


        $manager->flush();
    }

    public function CreateCategory(string $name, Categories $parent= null, ObjectManager $manager)
    {
        $category = new Categories();

        $category->setName($name)
                 ->setSlug($this->slugger->slug($category->getName())->lower())
                 ->setParent($parent);
        $manager->persist( $category);
        $this->addReference('cat-'.$this->count, $category);
        $this->count++;
        return $category;

    }
}
