<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MBOReport extends Constraint
{
    public $message = 'weight "{{ total }}" exceeds the limit {{ limit }}.';

    public function getTargets()
    {
      return self::CLASS_CONSTRAINT;
    }
}
