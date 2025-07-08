<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class MBOWeight extends Constraint
{
    public $message = 'weight "{{ weight }} + {{ total }}" exceeds the limit {{ limit }}.';

    public function getTargets(): array|string 
    {
      return self::CLASS_CONSTRAINT;
    }
}
