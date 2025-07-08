<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\PersonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 255)]
    private $name;

    #[ORM\Column(type: "string", length: 255)]
    private $email;


    /**
     * @var string The hashed password
     */
    #[ORM\Column(type: "string")]
    private $password;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: "manager")]
    private $employees;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: "employees")]
    private $manager;

    #[ORM\OneToMany(targetEntity: "App\Entity\ObjectiveEntry", mappedBy: "by_manager")]
    private $objective_entries;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $full_name;

    #[ORM\Column(type: "string", length: 64, nullable: true)]
    private $ldap_name;

    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $title;

    #[ORM\Column(type: "boolean")]
    private $active = false;

    #[ORM\ManyToOne(targetEntity: self::class)]
    private $reviewer;

    public function __construct()
    {
        $this->employees = new ArrayCollection();
        $this->objective_entries = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
      return $this->getName();
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

    public function getPassword() : ?string 
    {
      return (string) $this->password;
    }
    public function setPassword(string $password) : self 
    {
      $this->password = $password;
      return $this;
    }
    public function getSalt() {}
    public function getRoles(): array {
        static $admins = [];
        if (!$admins) {
            $admins = ['yakoub.a', 'manager-1'];
        }
        $roles = ['ROLE_USER'];
        if (in_array($this->name, $admins)) {
            $roles[] = 'ROLE_ADMIN';
        }
        return $roles;
    }
    public function eraseCredentials(): void {}

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

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(?string $full_name): self
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getLdapName(): ?string
    {
        return $this->ldap_name;
    }

    public function setLdapName(string $ldap_name): self
    {
        $this->ldap_name = $ldap_name;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getReviewer(): ?self
    {
        return $this->reviewer;
    }

    public function setReviewer(?self $reviewer): self
    {
        $this->reviewer = $reviewer;

        return $this;
    }
}
