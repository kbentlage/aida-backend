<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LogicalDeviceRepository")
 */
class LogicalDevice
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
    private $physical_device_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $property;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(type="boolean")
     */
    private $dataSave;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhysicalDeviceId(): ?int
    {
        return $this->physical_device_id;
    }

    public function setPhysicalDeviceId(int $physical_device_id): self
    {
        $this->physical_device_id = $physical_device_id;

        return $this;
    }

    public function getProperty(): ?string
    {
        return $this->property;
    }

    public function setProperty(string $property): self
    {
        $this->property = $property;

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

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getDataSave(): ?bool
    {
        return $this->dataSave;
    }

    public function setDataSave(bool $dataSave): self
    {
        $this->dataSave = $dataSave;

        return $this;
    }
}
