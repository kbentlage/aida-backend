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
use App\Entity\DeviceValue;
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
        $deviceValueRepository      = $this->em->getRepository(DeviceValue::class);

        // find logical device
        $logicalDevice = $logicalDeviceRepository->find($id);

        if($logicalDevice)
        {
            // get device value
            $deviceValue = $deviceValueRepository->findOneByLogicalDeviceId($logicalDevice->getId());

            // device has a current value
            if($deviceValue)
            {
                // if new value is different than current one
                if($deviceValue->getValue() != $value)
                {
                    $deviceValue->setValue($value);
                    $deviceValue->setModifyDate(new \DateTime('now'));

                    $this->em->persist($deviceValue);
                }
            }
            // device don't have a current value, create one
            else
            {
                $newDeviceValue = new DeviceValue();

                $newDeviceValue->setLogicalDeviceId($logicalDevice->getId());
                $newDeviceValue->setValue($value);
                $newDeviceValue->setCreateDate(new \DateTime('now'));

                $this->em->persist($newDeviceValue);
            }

            // if value should be saved as data
            if($logicalDevice->getDataSave())
            {
                $data = new Data;

                $data->setLogicalDeviceId($logicalDevice->getId());
                $data->setValue($value);
                $data->setCreateDate(new \DateTime('now'));
                $data->setModifyDate(new \DateTime('now'));
                $data->setUpdates(1);

                $this->em->persist($data);
            }
        }
    }
}