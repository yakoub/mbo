<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\objectivemanagementrepository;
use App\Entity\Person;

#[ORM\Entity(repositoryClass: objectivemanagementrepository::class)]
class ObjectiveManagement
{
    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: "string", length: 64)]
    #[Assert\Choice(array("work_in_progress", "under_review", "require_approval", "approved"))]
    private $status;

    #[ORM\Column(type: "float", nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    #[Assert\LessThanOrEqual(100)]
    private $vp_weight;

    #[ORM\Column(type: "float", nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    #[Assert\LessThanOrEqual(100)]
    private $ceo_weight;

    #[ORM\ManyToOne(targetEntity: Person::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $for_employee;

    #[ORM\ManyToOne(targetEntity: Person::class, inversedBy: "objective_entries")]
    #[ORM\JoinColumn(nullable: false)]
    private $by_manager;

    #[ORM\Column(type: "smallint")]
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

    public function getStatusWithLabel() {
        $config = array(
            'work_in_progress' => ['Work in progress', 'bg-secondary text-white'],
            'under_review' => ['Under review', 'bg-info text-white'],
            'require_approval' => ['Require approval', 'bg-primary text-white'],
            'approved' => ['Approved', 'bg-success text-white'],
        );
        return (object) array(
            'label' => $config[$this->status][0],
            'class' => $config[$this->status][1]
        );
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

    public function getByManager(): ?Person
    {
        return $this->by_manager;
    }

    public function setByManager(?Person $by_manager): self
    {
        $this->by_manager = $by_manager;

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

    static $transition = NULL; 
    static function setTransition() {
        self::$transition = array(
            'work_in_progress' => [NULL, 'under_review'],
            'under_review' => ['work_in_progress', 'require_approval'],
            'require_approval' => ['under_review', 'approved'],
            'approved' => ['require_approval', NULL]
        );
    }
    public function statusNext() {
        $this->statusTransition(1);
    }
    public function statusPrev() {
        $this->statusTransition(0);
    }
    function statusTransition($op) {
        if (!self::$transition) {
            self::setTransition();
        }
        $status_new = self::$transition[$this->status][$op];
        if ($status_new) {
            $this->status = $status_new;
        }
    }
}
