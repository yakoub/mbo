<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Person;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PersonFixture extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $pass_hasher) {
    }

    function createRoot(ObjectManager $manager) {
      $root = new Person();
      $root->setName('manager-root');
      $root->setPassword($this->pass_hasher->hashPassword(
        $root,
        'pass-root',
      ));

      $root->setFullName('manager-root');
      $root->setEmail($root->getName() . '@dd.d');
      $root->setActive(true);
      $manager->persist($root);
      return $root;
    }

    public function load(ObjectManager $manager): void
    {
        $root = $this->createRoot($manager);
        foreach ([1, 2] as $f) {
          $first = new Person();
          $first->setName('manager-' . $f);
          $first->setPassword($this->pass_hasher->hashPassword(
            $first,
            'pass-' . $f,
          ));

          $first->setFullName('manager-' . $f);
          $first->setEmail($first->getName() . '@dd.d');
          $first->setActive(true);
          $first->setManager($root);
          $manager->persist($first);

          foreach (range(1, 10) as $s) {
            $second = new Person();
            $second->setName("manager-$f-$s");
            $second->setPassword($this->pass_hasher->hashPassword(
              $second,
              "pass-$f-$s",
            ));
            $second->setFullName("manager-$f-$s");
            $second->setEmail($second->getName() . '@dd.d');
            $second->setManager($first);
            $second->setReviewer($first);
            $manager->persist($second);
            $this->addReference("manager-$f-$s", $second);

            foreach (range(1, 5) as $t) {
              $third = new Person();
              $third->setName("employee-$f-$s-$t");
              $third->setPassword($this->pass_hasher->hashPassword(
                $third,
                "pass-$f-$s-$t",
              ));
              $third->setFullName("employee-$f-$s-$t");
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
