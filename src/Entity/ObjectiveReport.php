<?php

namespace App\Entity;
use App\Validator as MBOValidator;

/**
 * @MBOValidator\MBOReport(groups={"report"})
 */
class ObjectiveReport
{
    public $management;
    public $objectivesDirect = [];
    public $objectivesIndirect = [];
    public $objectivesInfrastructure = [];
}
