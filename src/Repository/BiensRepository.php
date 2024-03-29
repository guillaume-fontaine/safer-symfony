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

    /* la partie formdata keyword doit etre une string avec "% and % entre les mot clefs
    */
    public function goodsfromIdandForm($id, $formData){
        $query = $this->createQueryBuilder('bien')
                ->where('bien.categorie = :id')
                ->setParameter('id', $id);
        if(!is_null($formData['mot_clefs'])){
//me demande pas pk isEmpty ne fonctionnait pas, je n'en sais rien
//(mais je pense que le probleme a lieu entre la chaise et le clavier)
            $count = 0;
            foreach(explode(" ",$formData['mot_clefs']) as $clef){
                if($count==0){
                    $query  ->andwhere('bien.intitule LIKE :mclef')
                            ->orwhere('bien.descriptif LIKE :mclef')
                            ->setParameter('mclef', '%'.$clef.'%');
                    $count++;
                }
                else {
                    $query  ->orwhere('bien.intitule LIKE :mclef'.$count)
                            ->orwhere('bien.descriptif LIKE :mclef'.$count)
                            ->setParameter('mclef'.$count, '%'.$clef.'%');
                    $count++;
                }
            }
        }
        if(!is_null($formData['prix_min'])){
            $query  ->andwhere('bien.prix >= :pmin')
                    ->setParameter('pmin', $formData['prix_min']);
        }
        if(!is_null($formData['prix_max']) && $formData['prix_max'] > $formData['prix_min']){
            $query  ->andwhere('bien.prix <= :pmax')
                    ->setParameter('pmax', $formData['prix_max']);
        }
        if(!is_null($formData['localisation'])){
            $query  ->andwhere('bien.localisation = :localisation')
                    ->setParameter('localisation', $formData['localisation']);
        }
        //dd($query);
        return $query ->orderBy('bien.prix','ASC') ->getQuery()->getResult();
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
