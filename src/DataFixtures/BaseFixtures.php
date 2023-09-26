<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Xvladqt\Faker\LoremFlickrProvider;

abstract class BaseFixtures extends Fixture
{
    protected ObjectManager $manager;
    protected Generator $faker;

    #[\ReturnTypeWillChange]
    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Factory::create('it_IT');
        $this->faker->addProvider(new LoremFlickrProvider($this->faker));

        $this->loadData($manager);
    }

    abstract protected function loadData(ObjectManager $manager): void;

    protected function createMany(string $className, int $count, callable $factory, array $params = []): void
    {
        for ($i = 0; $i < $count; $i++) {
            /** @param class-string $className */
            $entity = new $className(...$params);
            $factory($entity, $i);
            $this->manager->persist($entity);
            // store for usage later than App\Entity\ClassName_#COUNT#
            $this->addReference($className . '_' . $i, $entity);
        }
    }
}
