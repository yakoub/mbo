<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MBOReportValidator extends ConstraintValidator
{
    public function validate($report, Constraint $constraint)
    {
        $total_weight = 0;

        foreach (['Direct', 'Indirect', 'Infrastructure'] as $partition) {
            $property = 'objectives' . $partition;
            foreach ($report->{$property} as $entry) {
                $achieve = $entry->getAchieve() ?: 100;
                $total_weight += $entry->getWeight() * ((float)$achieve/100);
            }
        }

        $limit = false;

        if ($total_weight > 80) {
            $limit = '80%';
        }
        else {
            $total_weight += $report->management->getCeoWeight() * 0.1;
            $total_weight += $report->management->getVpWeight() * 0.1;

            if ($total_weight > 100) {
                $limit = '100%';
            }
        }

        if ($limit) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ total }}', $total_weight)
                ->setParameter('{{ limit }}', $limit)
                ->addViolation();
        }
    }
}
