<?php

namespace App\Repository;

use App\Entity\Favoriser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Favoriser>
 *
 * @method Favoriser|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favoriser|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favoriser[]    findAll()
 * @method Favoriser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favoriser::class);
    }

    public function save(Favoriser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Favoriser $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getFavoriserByFavoris($value): array
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.favoris = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getBiensFavoriserThisDay(){
        return $this->createQueryBuilder('f')
        ->select('count(f.biens) as nbBien , b as biens')
            ->from('App\Entity\Favoris', "g")
            ->from('App\Entity\Biens', "b")
            ->andWhere('g.date = :val')
            ->andWhere('g.id = f.favoris')
            ->andWhere('b.id = f.biens')
            ->setParameter('val', date('Y-m-d'))
            ->groupBy('b')
            ->orderBy('count(f.biens)', 'DESC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return Favoriser[] Returns an array of Favoriser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Favoriser
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}