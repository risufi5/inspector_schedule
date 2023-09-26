<?php

declare(strict_types=1);

namespace App\Dto\Response;

use App\Entity\Job;
use JMS\Serializer\Annotation as JMS;
use OpenApi\Attributes as OA;

class JobResponse
{
    #[JMS\Expose]
    #[JMS\Groups(['jobs'])]
    #[OA\Property(example: 1)]
    protected ?int $id;

    #[JMS\Expose]
    #[JMS\Groups(['jobs'])]
    #[OA\Property(example: 'Work description')]
    protected ?string $description;

    #[JMS\Expose]
    #[JMS\Groups(['jobs'])]
    #[OA\Property(example: 'new')]
    protected ?string $status;

    public function __construct(Job $job)
    {
        $this->id = $job->getId();
        $this->description = $job->getDescription();
        $this->status = $job->getStatus();
    }
}