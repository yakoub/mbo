<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use App\Repository\MBOYearlyRepository;

class MBOWeightValidator extends ConstraintValidator
{
    private $repository;

    public function __construct(MBOYearlyRepository $repository) {
        $this->repository = $repository;
    }

    public function validate($mbo_yearly, Constraint $constraint)
    {
        $year = $mbo_yearly->getYear();
        $employee = $mbo_yearly->getForEmployee();
        $total_weight = $this->repository->aggregateYearForEmployee($year, $employee);

        $weight = $mbo_yearly->getWeight();

        if ($weight + $total_weight > 0.8) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ weight }}', $weight)
                ->setParameter('{{ total }}', $total_weight)
                ->setParameter('{{ limit }}', "80%")
                ->addViolation();
        }
    }
}
