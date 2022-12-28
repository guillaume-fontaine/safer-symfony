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

    //Cette fonction renvoie entre 0 et 3 biens aleatoire
    //Elle passe par une native query car le query builder n'inclus pas de rand
    public function randomZerotoThreeBiens() : array
    {
        # set entity name
        $table = $this->getClassMetadata()->getTableName();

        $rsm = new ORM\Query\ResultSetMapping();
        $rsm->addEntityResult($this->getEntityName(), 'biens');
        $rsm->addFieldResult('biens', 'intitule', 'intitule');
        $rsm->addFieldResult('biens', 'categorie', 'categorie');
        $rsm->addFieldResult('biens', 'descriptif', 'descriptif');
        $rsm->addFieldResult('biens', 'prix', 'prix');
        $rsm->addFieldResult('biens', 'localisation', 'localisation');
        $rsm->addFieldResult('biens', 'surface', 'surface');
        $rsm->addFieldResult('biens', 'reference', 'reference');

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createNativeQuery(
            "SELECT intitule, categorie_id, descriptif, prix, localisation, surface, reference
            FROM biens
            ORDER BY RAND()
            LIMIT 3", $rsm
        );
        return $query->getResult();
    }

    public function qsqldocusymfony(){
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM biens
            ORDER BY RAND()
            LIMIT 3
            ';
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        // returns an array of arrays (i.e. a raw data set)
        return $resultSet->fetchAllAssociative();
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
