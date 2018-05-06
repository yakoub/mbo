<?php

namespace App\DataFixtures;

use App\Entity\ObjectiveEntry;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ObjectiveEntryFixture extends Fixture implements DependentFixtureInterface
{
    public function getDependencies() 
    {
        return [PersonFixture::class];
    }

    public function load(ObjectManager $object_manager)
    {
        foreach ([1, 2] as $f) {
            foreach ([1, 2, 3] as $t) {

                foreach ([2015, 2016, 2017] as $year) {
                    $objective = $this->createObjective($f, $f, $t, $year);
                    $object_manager->persist($objective);
                }
            }
            $object_manager->flush();
        }
    }

    function createObjective($f, $s, $t, $year) {
        $by_manager = $this->getReference("manager-$f-$f");
        $for_employee = $this->getReference("employee-$f-$f-$t");

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
