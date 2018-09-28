<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhysicalDeviceRepository")
 */
class PhysicalDevice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $device_type_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $area_id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeviceTypeId(): ?int
    {
        return $this->device_type_id;
    }

    public function setDeviceTypeId(int $device_type_id): self
    {
        $this->device_type_id = $device_type_id;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAreaId(): ?int
    {
        return $this->area_id;
    }

    public function setAreaId(int $area_id): self
    {
        $this->area_id = $area_id;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }
}
