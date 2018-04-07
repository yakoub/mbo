<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Person", mappedBy="manager")
     */
    private $employees;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="employees")
     */
    private $manager;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MBOYearly", mappedBy="by_manager")
     */
    private $mbo_yearlies;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
        $this->mbo_yearlies = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getManager() : ?Person
    {
        return $this->manager;
    }

    public function setManager(Person $person): self
    {
        $this->manager = $person;
        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Person $employee): self
    {
        if (!$this->employees->contains($employee)) {
            $this->employees[] = $employee;
            $employee->setManager($this);
        }

        return $this;
    }

    public function removeEmployee(Person $employee): self
    {
        if ($this->employees->contains($employee)) {
            $this->employees->removeElement($employee);
            // set the owning side to null (unless already changed)
            if ($employee->getManager() === $this) {
                $employee->setManager(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MBOYearly[]
     */
    public function getMboYearlies(): Collection
    {
        return $this->mbo_yearlies;
    }

    public function addMboYearly(MBOYearly $mboYearly): self
    {
        if (!$this->mbo_yearlies->contains($mboYearly)) {
            $this->mbo_yearlies[] = $mboYearly;
            $mboYearly->setByManager($this);
        }

        return $this;
    }

    public function removeMboYearly(MBOYearly $mboYearly): self
    {
        if ($this->mbo_yearlies->contains($mboYearly)) {
            $this->mbo_yearlies->removeElement($mboYearly);
            // set the owning side to null (unless already changed)
            if ($mboYearly->getByManager() === $this) {
                $mboYearly->setByManager(null);
            }
        }

        return $this;
    }

    public function __toString() {
      return $this->getName();
    }
}
