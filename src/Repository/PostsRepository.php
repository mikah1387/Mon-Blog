<?php

namespace App\Repository;

use App\Entity\Posts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Posts>
 *
 * @method Posts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posts[]    findAll()
 * @method Posts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posts::class);
    }

     public function findUsersActif()
     {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT nickname,image, count(*) AS nombre_articles from posts join users on posts.users_id = users.id group by users_id ORDER by nombre_articles DESC LIMIT 4 
            ';

        $resultSet = $conn->executeQuery($sql);

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();

     }

     public function searchTags($tags)
     {
        return $this->createQueryBuilder('p')
                   ->andWhere('MATCH_AGAINST(p.title,p.content) AGAINST (:tags boolean) > 0')
                   ->setParameter('tags', $tags)
                   
                   ->getQuery()
                   ->getResult();

     }
//    /**
//     * @return Posts[] Returns an array of Posts objects
//     */
   public function findCachePosts(): array
   {
       return $this->createQueryBuilder('p')
           
      
           ->leftJoin('p.users', 'u')
           ->addSelect('u')   
           ->leftJoin('p.categories', 'c')
           ->addSelect('c')
           ->orderBy('p.Created_at', 'ASC')
           ->getQuery()
           ->getResult()
       ;
   }

   public function findPostsBycaty($idcat =null, $trie='DESC')
   {
        $query= $this->createQueryBuilder('p')
                   ->leftJoin('p.categories', 'c')
                   ->addSelect('c');
                   if($idcat !== null){
                    $query->andWhere('c.id = :val')
                    ->setParameter('val', $idcat);
                   }
                   
                   $query->orderBy('p.Created_at', $trie);
                //    ->setParameter('value', $trie)
                    
                 return $query->getQuery()->getResult();
       
                 
   }
}
