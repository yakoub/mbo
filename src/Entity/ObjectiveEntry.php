<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\PreFlushEventArgs;
use App\Validator as MBOValidator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ObjectiveEntryRepository")
 * @ORM\HasLifecycleCallbacks()
 * @MBOValidator\MBOWeight(groups={"single_update"})
 */

class ObjectiveEntry
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person", inversedBy="objective_entries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $by_manager;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Person")
     * @ORM\JoinColumn(nullable=false)
     */
    private $for_employee;

    /**
     * @ORM\Column(type="smallint")
     */
    private $year;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\Choice({"Direct", "Indirect", "Infrastructure"})
     */
    private $Type;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $subject;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\LessThanOrEqual(100)
     */
    private $weight;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\LessThanOrEqual(100)
     */
    private $achieve;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuadWeight", cascade={"persist", "remove"})
     */
    private $quad_weight;

    /**
     * @ORM\PreFlush
     */
    public function removeEmptyQuadWeight(PreFlushEventArgs $event) {
        if (!$this->quad_weight) {
            return;
        }
        $quad = $this->quad_weight->getArray();
        foreach ($quad as $weight) {
            if ($weight) {
                return;
            }
        }
        $event->getEntityManager()->remove($this->quad_weight);
        $this->quad_weight = NULL;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getByManager(): ?Person
    {
        return $this->by_manager;
    }

    public function setByManager(?Person $by_manager): self
    {
        $this->by_manager = $by_manager;

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

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getSubject(): ?string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getAchieve(): ?float
    {
        return $this->achieve;
    }

    public function setAchieve(?float $achieve): self
    {
        $this->achieve = $achieve;

        return $this;
    }

    public function getQuadWeight(): ?QuadWeight
    {
        return $this->quad_weight;
    }

    public function setQuadWeight(?QuadWeight $quad_weight): self
    {
        $this->quad_weight = $quad_weight;

        return $this;
    }
}
