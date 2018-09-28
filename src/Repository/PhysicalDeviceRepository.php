<?php

namespace App\Repository;

use App\Entity\PhysicalDevice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PhysicalDevice|null find($id, $lockMode = null, $lockVersion = null)
 * @method PhysicalDevice|null findOneBy(array $criteria, array $orderBy = null)
 * @method PhysicalDevice[]    findAll()
 * @method PhysicalDevice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhysicalDeviceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PhysicalDevice::class);
    }

//    /**
//     * @return PhysicalDevice[] Returns an array of PhysicalDevice objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PhysicalDevice
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
