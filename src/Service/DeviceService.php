<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 03/10/2018
 * Time: 21:36
 */

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\LogicalDevice;
use App\Entity\Value;
use App\Entity\Data;

class DeviceService
{
    protected $em;

    /**
     * DeviceService constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Update
     *
     * @param $id
     * @param $value
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function update($id, $value)
    {
        // initialize repositories
        $logicalDeviceRepository    = $this->em->getRepository(LogicalDevice::class);

        // find logical device
        $logicalDevice = $logicalDeviceRepository->find($id);

        if($logicalDevice)
        {
            // if value is changed, update it
            if($logicalDevice->getValue() != $value)
            {
                $logicalDevice->setValue($value);
                $logicalDevice->setValueDate(new \DateTime('now'));

                $this->em->persist($logicalDevice);
            }

            // if value should be saved as data
            if($logicalDevice->getDataSave())
            {
                $data = new Data;

                $data->setLogicalDeviceId($logicalDevice->getId());
                $data->setValue($value);
                $data->setUpdates(1);

                $this->em->persist($data);
            }
        }
    }
}