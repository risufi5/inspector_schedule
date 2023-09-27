<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Inspector;
use App\Enum\InspectorLocationEnum;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class InspectorFixture extends BaseFixtures implements FixtureGroupInterface
{
    final public const COUNT_ELEMENTS = 3;

    public static function getGroups(): array
    {
        return ['inspectors'];
    }

    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(className: Inspector::class, count: self::COUNT_ELEMENTS, factory: function (Inspector $inspector) use ($manager) {
           $name = $this->faker->name();
           $inspector->setName($name);

           /** @var InspectorLocationEnum $inspectorLocation */
           $inspectorLocation = $this->faker->randomElement(InspectorLocationEnum::cases());
           $inspector->setLocation($inspectorLocation);

            $currentTime = new \DateTime();
            $currentTime->format('Y-m-d H:i:s');
            $inspector
                ->setCreatedAt($currentTime)
                ->setUpdatedAt($currentTime);

            $manager->persist($inspector);
        });

        $manager->flush();
    }
}
