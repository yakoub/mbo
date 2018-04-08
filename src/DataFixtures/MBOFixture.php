<?php

namespace App\DataFixtures;

use App\Entity\MBOYearly;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MBOFixture extends Fixture implements DependentFixtureInterface
{
    public function getDependencies() 
    {
        return [PersonFixture::class];
    }

    public function load(ObjectManager $object_manager)
    {
        foreach ([1, 2] as $f) {
            foreach ([1, 2, 3] as $t) {
                $by_manager = $this->getReference("manager-$f-$f");
                $for_employee = $this->getReference("employee-$f-$f-$t");
                foreach ([2015, 2016, 2017] as $year) {
                    $mbo = $this->createMBO($f, $f, $t, $by_manager, $for_employee, $year);
                    $object_manager->persist($mbo);
                }
            }
            $object_manager->flush();
        }
    }

    function createMBO($f, $s, $t, $manager, $employee, $year) {
        $mbo = new MBOYearly();
        $mbo->setByManager($manager);
        $mbo->setForEmployee($employee);
        $mbo->setYear($year);
        $mbo->setType('type');
        $mbo->setSubject("subject-$f-$f-$t");
        $mbo->setWeight(0.2);
        $mbo->setStatus('good');
        $mbo->setScore(0.3);
        return $mbo;
    }
}
