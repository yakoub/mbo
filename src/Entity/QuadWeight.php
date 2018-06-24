<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuadWeightRepository")
 */
class QuadWeight
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $first;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $second;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $third;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $fourth;

    public function getId()
    {
        return $this->id;
    }

    public function getArray() {
      return [$this->first, $this->second, $this->third, $this->fourth];
    }

    public function getFirst(): ?float
    {
        return $this->first;
    }

    public function setFirst(?float $first): self
    {
        $this->first = $first;

        return $this;
    }

    public function getSecond(): ?float
    {
        return $this->second;
    }

    public function setSecond(?float $second): self
    {
        $this->second = $second;

        return $this;
    }

    public function getThird(): ?float
    {
        return $this->third;
    }

    public function setThird(?float $third): self
    {
        $this->third = $third;

        return $this;
    }

    public function getFourth(): ?float
    {
        return $this->fourth;
    }

    public function setFourth(?float $fourth): self
    {
        $this->fourth = $fourth;

        return $this;
    }
}
