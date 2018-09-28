<?php

namespace App\Repository;

use App\Entity\DeviceValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DeviceValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeviceValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeviceValue[]    findAll()
 * @method DeviceValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceValueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DeviceValue::class);
    }

    /**
     * Find One By Logical Device ID
     *
     * @param $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByLogicalDeviceId($id)
    {
        return $this->createQueryBuilder('device_value')
            ->andWhere('device_value.logical_device_id = :logical_device_id')
            ->setParameter('logical_device_id', $id)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
