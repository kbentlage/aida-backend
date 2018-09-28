<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeviceValueRepository")
 */
class DeviceValue
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
    private $logical_device_id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $value_float;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value_string;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $modify_date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogicalDeviceId(): ?int
    {
        return $this->logical_device_id;
    }

    public function setLogicalDeviceId(int $logical_device_id): self
    {
        $this->logical_device_id = $logical_device_id;

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

    public function getCreateDate(): ?\DateTimeInterface
    {
        return $this->create_date;
    }

    public function setCreateDate(\DateTimeInterface $create_date): self
    {
        $this->create_date = $create_date;

        return $this;
    }

    public function getModifyDate(): ?\DateTimeInterface
    {
        return $this->modify_date;
    }

    public function setModifyDate(?\DateTimeInterface $modify_date): self
    {
        $this->modify_date = $modify_date;

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
}