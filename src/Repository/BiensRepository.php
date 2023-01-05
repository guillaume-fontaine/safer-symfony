<?php

namespace App\Repository;

use App\Entity\Biens;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM;

/**
 * @extends ServiceEntityRepository<Biens>
 *
 * @method Biens|null find($id, $lockMode = null, $lockVersion = null)
 * @method Biens|null findOneBy(array $criteria, array $orderBy = null)
 * @method Biens[]    findAll()
 * @method Biens[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BiensRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Biens::class);
    }

    public function save(Biens $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Biens $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //Cette fonction recupere 3 bien aleatoire via une requete SQL
    public function threeRandomGoods(){
        return $this->createQueryBuilder('bien')->orderBy('RAND()')->setMaxResults(3)->getQuery()->getResult();
    }

    public function allGoodsfromCategorie($id)
    {
        return $this->createQueryBuilder('bien')
                    ->where('bien.categorie = :id')
                    ->setParameter('id', $id)
                    ->orderBy('bien.prix','ASC')
                    ->getQuery()->getResult();
    }

    /* la partie formdata keyword doit etre une string avec % and % entre les mot clefs
    */
    public function goodsfromIdandForm($id, $formData){
        
        return $this->createQueryBuilder('bien')
                    ->where('bien.categorie = :id')
                    ->setParameter('id', $id)
                    ->andwhere('bien.intitule LIKE :keyword')
                    ->orwhere('bien.descriptif LIKE :keyword')
                    ->setParameter('keyword', '%'.$formData['mot_clef'].'%')
                    ->orderBy('bien.prix','ASC')
                    ->getQuery()->getResult();
    }


//    /**
//     * @return Biens[] Returns an array of Biens objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Biens
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
