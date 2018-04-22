<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PersonRepository")
 */
class Person implements UserInterface, \Serializable
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
     * @ORM\OneToMany(targetEntity="App\Entity\ObjectiveEntry", mappedBy="by_manager")
     */
    private $objective_entries;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
        $this->objective_entries = new ArrayCollection();
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

    public function getUsername(): ?string {
        return $this->name;
    }

    public function getPassword() {}
    public function getSalt() {}
    public function getRoles() {
        return ['ROLE_USER'];
    }
    public function eraseCredentials() {}

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->name,
        ));
    }

    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->name,
        ) = unserialize($serialized, ['allowed_classes' => false]);
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
     * @return Collection|ObjectiveEntry[]
     */
    public function getObjectiveEntries(): Collection
    {
        return $this->objective_entries;
    }

    public function addObjectiveEntry(ObjectiveEntry $objective): self
    {
        if (!$this->objective_entries->contains($objective)) {
            $this->objective_entries[] = $objective;
            $objective->setByManager($this);
        }

        return $this;
    }

    public function removeObjectiveEntry(ObjectiveEntry $objective): self
    {
        if ($this->objective_entries->contains($objective)) {
            $this->objective_entries->removeElement($objective);
            // set the owning side to null (unless already changed)
            if ($objective->getByManager() === $this) {
                $objective->setByManager(null);
            }
        }

        return $this;
    }

    public function __toString() {
      return $this->getName();
    }
}
