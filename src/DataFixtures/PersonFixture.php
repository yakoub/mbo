<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Person;

class PersonFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach (range(1, 10) as $f) {
          $first = new Person();
          $first->setName('manager-' . $f);
          $first->setEmail($first->getName() . '@dd.d');
          $manager->persist($first);

          foreach (range(1, 10) as $s) {
            $second = new Person();
            $second->setName("manager-$f-$s");
            $second->setEmail($second->getName() . '@dd.d');
            $second->setManager($first);
            $manager->persist($second);
            $this->addReference("manager-$f-$s", $second);

            foreach (range(1, 5) as $t) {
              $third = new Person();
              $third->setName("employee-$f-$s-$t");
              $third->setEmail($third->getName() . '@dd.d');
              $third->setManager($second);
              $manager->persist($third);
              $this->addReference("employee-$f-$s-$t", $third);
            }
          }
          $manager->flush();
        }
    }
}
