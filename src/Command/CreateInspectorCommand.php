<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Inspector;
use App\Enum\InspectorLocationEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateInspectorCommand extends Command
{
    protected static $defaultName = 'app:create-inspector';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Create new inspectors.')
            ->setHelp('This command allows you to create new inspectors in the database.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->getValues() as $data) {
            $inspector = new Inspector();
            $currentTime =  new \DateTime();
            $currentTime->format('Y-m-d H:i:s');
            $inspector
                ->setName($data['name'])
                ->setLocation($data['location'])
                ->setCreatedAt($currentTime)
                ->setUpdatedAt($currentTime);

            $this->entityManager->persist($inspector);
        }
        $this->entityManager->flush();

        $output->writeln('Inspectors created successfully.');

        return Command::SUCCESS;
    }

    private function getValues(): array
    {
        return [
            [
                'name' => 'John',
                'location' => InspectorLocationEnum::MADRID,
            ],
            [
                'name' => 'Chris',
                'location' => InspectorLocationEnum::MEXICO_CITY,
            ],
            [
                'name' => 'Evan',
                'location' => InspectorLocationEnum::UK,
            ],
        ];
    }

    }