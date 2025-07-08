<?php

namespace App\DataFixtures;

use App\Entity\ObjectiveEntry;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Person;

class ObjectiveEntryFixture extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array 
    {
        return [PersonFixture::class];
    }

    public function load(ObjectManager $object_manager): void
    {
        foreach ([1, 2] as $f) {
            foreach ([1, 2, 3] as $t) {

                foreach ([2024, 2025, 2026] as $year) {
                    $objective = $this->createObjective($f, $f, $t, $year);
                    $object_manager->persist($objective);
                }
            }
            $object_manager->flush();
        }
    }

    function createObjective($f, $s, $t, $year) {
        $by_manager = $this->getReference("manager-$f-$f", Person::class);
        $for_employee = $this->getReference("employee-$f-$f-$t", Person::class);

        $objective = new ObjectiveEntry();
        $objective->setByManager($by_manager);
        $objective->setForEmployee($for_employee);
        $objective->setYear($year);
        $objective->setType('Direct');
        $objective->setSubject("subject-$f-$f-$t");
        $objective->setWeight(20);
        $objective->setAchieve(30);
        return $objective;
    }
}
