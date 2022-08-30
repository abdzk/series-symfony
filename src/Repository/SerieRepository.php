<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function add(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBestSeries()
    {
        //en DQL
        $entityManager = $this->getEntityManager(); //récuoeration de entitymanager
        $dql="
                SELECT s            
                FROM App\Entity\Serie s
                WHERE s.popularity > 100
                AND s.vote >8
                ORDER BY s.popularity DESC"; //chaine de DQL

                $query=$entityManager->createQuery($dql);//je récupere un objet query
                $query->setMaxResults(50);      //je peux limiter mon nombre de résulats récupérer
                $results = $query->getResult();         //je récupére le résultat en tableau
                //$results = $query->getOne(); //retourné l'instance de la classe
                $query->setMaxResults(50);      //je peux limiter mon nombre de résulats récupérer
                $results = $query->getResult();         //je récupére le résultat en tableau
                return $results;

        /*
        //version QueryBuilder
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder = andWhere('s.popularity >100');
        $queryBuilder = andWhere('s.vote'>8);
        $queryBuilder = addOrderBy('s.popularity','DESC');
        $query = $queryBuilder->getQuery();

        $query->setMaxResults(50);      //je peux limiter mon nombre de résulats récupérer
        $results = $query->getResult();         //je récupére le résultat en tableau
        return $results;*/

    }


//    /**
//     * @return Serie[] Returns an array of Serie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Serie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
