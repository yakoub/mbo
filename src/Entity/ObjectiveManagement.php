<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Choice({"work_in_progress", "under_review", "require_approval", "approved"})
     */
    private $status;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\LessThanOrEqual(100)
     */
    private $vp_weight;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\LessThanOrEqual(100)
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
            $this->notify();
        }
    }

    function notify() {
        switch($this->status) {
            case 'work_in_progress':
                $subject = 'mbo requires more work';
                $template = 'emails/mbo-in-progress';
                //$to = $this->  ?
                break;
            case 'under_review':
                $subject = '';
                $template ='emails/mbo-';
                break;
            case 'require_approval':
                $subject = '';
                $template ='emails/mbo-';
                break;
            case 'approved':
                $subject = '';
                $template ='emails/mbo-';
                break;
        }
    }
}
