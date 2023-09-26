<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Job;
use App\Enum\JobStatusEnum;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class JobFixture extends BaseFixtures implements FixtureGroupInterface
{
    final public const COUNT_ELEMENTS = 5;

    public static function getGroups(): array
    {
        return ['jobs'];
    }

    protected function loadData(ObjectManager $manager): void
    {
        $this->createMany(className: Job::class, count: self::COUNT_ELEMENTS, factory: function (Job $job) use ($manager) {
            $description = $this->faker->text();
            $job->setDescription($description);

            /** @var JobStatusEnum $jobStatus */
            $jobStatus = $this->faker->randomElement(JobStatusEnum::cases());
            $job->setStatus($jobStatus);

            $currentTime = new \DateTime();
            $currentTime->format('Y-m-d H:i:s');
            $job
                ->setCreatedAt($currentTime)
                ->setUpdatedAt($currentTime);

            $manager->persist($job);
        });

        $manager->flush();
    }
}
