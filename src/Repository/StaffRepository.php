<?php

namespace App\Repository;

use App\Entity\Staff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Staff>
 *
 * @method Staff|null find($id, $lockMode = null, $lockVersion = null)
 * @method Staff|null findOneBy(array $criteria, array $orderBy = null)
 * @method Staff[]    findAll()
 * @method Staff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StaffRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Staff::class);
    }

    public function add(Staff $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Staff $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Staff[] Returns an array of Staff objects
    */
   public function searchByName($name): array
   {
       return $this->createQueryBuilder('st')
           ->select('st.image, st.id, st.name, st.birthday')
           ->where('st.name LIKE :name')
           ->setParameter('name', '%'.$name.'%')
        //    ->orderBy('s.id', 'ASC')
        //    ->setMaxResults(10)
           ->getQuery()
           ->getArrayResult()
       ;
   }

    // /**
    // * @return Staff[] Returns an array of Staff objects
    // */
    // public function hardWorking(): array
    // {
    //     $en = $this->getEntityManager()->getConnection();
    //     $sql ='
    //         SELECT st.image, st.id, st.name, COUNT(tk.staff_id) as NumberOfWorkingDays, MONTHNAME(tk.date) AS Month
    //         FROM `timekeeping` tk, `staff` st
    //         WHERE tk.staff_id = st.id
    //         GROUP BY tk.staff_id, MONTH(tk.date) 
    //         HAVING COUNT(tk.staff_id) > 28
    //     ';
    //     $stmt = $en->prepare($sql);
    //     $re = $stmt->executeQuery();
    //     return $re->fetchAllAssociative();
    // }


 /**
    * @return Staff[] Returns an array of Staff objects
    */
    public function hardWorking(): array
    {
        $en = $this->getEntityManager()->getConnection();
        $sql = '
        SELECT st.image, st.id, st.name, COUNT(tk.staff_id) as NumberOfWorkingDays, MONTHNAME(tk.date) AS Month
        FROM `timekeeping` tk, `staff` st
        WHERE tk.staff_id = st.id
        GROUP BY tk.staff_id, MONTH(tk.date) 
        HAVING COUNT(tk.staff_id) > 28
        ';
        $stmt = $en->prepare($sql);
        $re = $stmt->executeQuery();
        return $re->fetchAllAssociative();
    }




//    /**
//     * @return Staff[] Returns an array of Staff objects
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

//    public function findOneBySomeField($value): ?Staff
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
