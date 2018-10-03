<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

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

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $value_float;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value_string;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="change", field={"value_float", "value_string"})
     */
    private $value_change_date;

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

    public function getValueFloat(): ?float
    {
        return $this->value_float;
    }

    public function setValueFloat(?float $value_float): self
    {
        $this->value_float = $value_float;

        return $this;
    }

    public function getValueString(): ?string
    {
        return $this->value_string;
    }

    public function setValueString(?string $value_string): self
    {
        $this->value_string = $value_string;

        return $this;
    }

    public function getValue()
    {
        if($this->getValueString())
        {
            return $this->getValueString();
        }
        else
        {
            return $this->getValueFloat();
        }
    }

    public function setValue($value): self
    {
        if(is_numeric($value))
        {
            $this->setValueFloat($value);
        }
        else
        {
            $this->setValueString($value);
        }

        return $this;
    }

    public function getValueChangeDate(): ?\DateTimeInterface
    {
        return $this->value_change_date;
    }
}
