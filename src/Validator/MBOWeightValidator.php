<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use App\Repository\ObjectiveEntryRepository;

class MBOWeightValidator extends ConstraintValidator
{
    private $repository;

    public function __construct(ObjectiveEntryRepository $repository) {
        $this->repository = $repository;
    }

    public function validate($objective_entry, Constraint $constraint)
    {
        $year = $objective_entry->getYear();
        $employee = $objective_entry->getForEmployee();
        $total_weight = $this->repository->aggregateYearForEmployee($year, $employee, $objective_entry);

        $weight = $objective_entry->getWeight();

        if ($weight + $total_weight > 0.8) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ weight }}', $weight)
                ->setParameter('{{ total }}', $total_weight)
                ->setParameter('{{ limit }}', "80%")
                ->addViolation();
        }
    }
}
