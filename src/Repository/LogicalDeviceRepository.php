<?php

namespace App\Repository;

use App\Entity\LogicalDevice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method LogicalDevice|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogicalDevice|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogicalDevice[]    findAll()
 * @method LogicalDevice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogicalDeviceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LogicalDevice::class);
    }

    /**
     * Find One By ID and Property
     * @param $id
     * @param $property
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByIdAndProperty($id, $property)
    {
        return $this->createQueryBuilder('logical_device')
            ->andWhere('logical_device.physical_device_id = :id')
            ->andWhere('logical_device.property = :property')
            ->setParameter('id', $id)
            ->setParameter('property', $property)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
