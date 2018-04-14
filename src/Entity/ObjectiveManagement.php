<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ObjectiveManagementRepository")
 */
class ObjectiveManagement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $status;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $vp_weight;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $ceo_weight;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person")
     * @ORM\JoinColumn(nullable=false)
     */
    private $for_employee;

    /**
     * @ORM\Column(type="smallint")
     */
    private $year;

    public function getId()
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getVpWeight(): ?float
    {
        return $this->vp_weight;
    }

    public function setVpWeight(?float $vp_weight): self
    {
        $this->vp_weight = $vp_weight;

        return $this;
    }

    public function getCeoWeight(): ?float
    {
        return $this->ceo_weight;
    }

    public function setCeoWeight(?float $ceo_weight): self
    {
        $this->ceo_weight = $ceo_weight;

        return $this;
    }

    public function getForEmployee(): ?Person
    {
        return $this->for_employee;
    }

    public function setForEmployee(?Person $for_employee): self
    {
        $this->for_employee = $for_employee;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }
}
