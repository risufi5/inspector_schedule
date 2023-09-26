<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Job;
use App\Enum\JobStatusEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateJobCommand extends Command
{
    protected static $defaultName = 'app:create-job';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create new jobs.')
            ->setHelp('This command allows you to create new jobs in the database.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->getValues() as $data) {
            $job = new Job();
            $currentTime =  new \DateTime();
            $currentTime->format('Y-m-d H:i:s');
            $job
                ->setDescription($data['description'])
                ->setStatus($data['status'])
                ->setCreatedAt($currentTime)
                ->setUpdatedAt($currentTime);

            $this->entityManager->persist($job);
        }
        $this->entityManager->flush();

        $output->writeln('Jobs created successfully.');

        return Command::SUCCESS;
    }

    private function getValues(): array
    {
        return [
            [
                'description' => 'Description of the first job.',
                'status' => JobStatusEnum::NEW,
            ],
            [
                'description' => 'Description of the second job.',
                'status' => JobStatusEnum::NEW,
            ],
            [
                'description' => 'Description of the third job.',
                'status' => JobStatusEnum::NEW,
            ],
            [
                'description' => 'Description of the fourth job.',
                'status' => JobStatusEnum::NEW,
            ],
            [
                'description' => 'Description of the fifth job.',
                'status' => JobStatusEnum::NEW,
            ],
        ];
    }

}