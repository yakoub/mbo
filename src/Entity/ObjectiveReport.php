<?php

namespace App\Entity;
use App\Validator as MBOValidator;

/**
 * @MBOValidator\MBOReport(groups={"report"})
 */
class ObjectiveReport
{
    private $current_user;
    public function __construct(Person $user) {
        $this->current_user = $user;
    }
    public $management;
    public $objectivesDirect = [];
    public $objectivesIndirect = [];
    public $objectivesInfrastructure = [];

    public function getUserRole() {
        if ($this->current_user->getLdapName() == 'Guy Sella,CEO') {
            return 'ceo';
        }
        $by_manager = $this->management->getByManager();
        if ($this->current_user->getId() == $by_manager->getId()) {
            return 'manager';
        }
        $reviewer = $by_manager->getReviewer();
        if ( $reviewer && ($this->current_user->getId() == $reviewer->getId())) {
            return 'reviewer';
        }
        return 'undefined';
    }
}
