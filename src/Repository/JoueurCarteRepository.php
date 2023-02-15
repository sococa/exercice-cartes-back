<?php

namespace App\Repository;

use App\Entity\JoueurCarte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JoueurCarte>
 *
 * @method JoueurCarte|null find($id, $lockMode = null, $lockVersion = null)
 * @method JoueurCarte|null findOneBy(array $criteria, array $orderBy = null)
 * @method JoueurCarte[]    findAll()
 * @method JoueurCarte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JoueurCarteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JoueurCarte::class);
    }

    public function save(JoueurCarte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(JoueurCarte $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function countByCartesWithRelation()
    {
        $sql ='
            SELECT *
            FROM carte
            LEFT JOIN joueur_carte ON carte.id = joueur_carte.joueur_id
            WHERE joueur_carte.id IS NOT NULL
            GROUP BY chiffre
        ';
        return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
    }


//    /**
//     * @return JoueurCarte[] Returns an array of JoueurCarte objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?JoueurCarte
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
