<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function add(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Article $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function update(){
        $this->getEntityManager()->flush();
    }

    // public function findByAuthor($author):array{
    //     $dql = "
    //         SELECT a FROM App\Entity\Article a
    //         WHERE a.author LIKE :author
    //         AND a.isPublished = true
    //     ";
    //     $em = $this->getEntityManager();
    //     $query = $em->createQuery($dql);
    //     $query->setParameter(":author","%$author%");
    //     return $query->getResult();
    // }

    public function findByAuthor($author):array{

        $query = $this->createQueryBuilder("a")
                    -> andWhere("a.author LIKE :author")
                    -> andWhere("a.isPublished = true")
                    -> setParameter(":author","%$author%")
                    -> getQuery();
                    
        return $query->getResult();
    }

    public function findAllAuthor():array{
        $dql = "
            SELECT DISTINCT a.author FROM App\Entity\Article a            
            WHERE a.isPublished = true
        ";
        $em = $this->getEntityManager();
        $query = $em->createQuery($dql);
        return $query->getResult();
    }



//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
