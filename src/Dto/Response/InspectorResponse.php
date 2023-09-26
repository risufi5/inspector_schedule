<?php

declare(strict_types=1);

namespace App\Dto\Response;

use App\Entity\Inspector;
use JMS\Serializer\Annotation as JMS;
use OpenApi\Attributes as OA;

class InspectorResponse
{
    #[JMS\Expose]
    #[JMS\Groups(['inspectors'])]
    #[OA\Property(example: 1)]
    protected ?int $id;

    #[JMS\Expose]
    #[JMS\Groups(['inspectors'])]
    #[OA\Property(example: 'John')]
    protected ?string $name;

    #[JMS\Expose]
    #[JMS\Groups(['inspectors'])]
    #[OA\Property(example: 'Madrid')]
    protected ?string $location;

    public function __construct(Inspector $inspector)
    {
        $this->id = $inspector->getId();
        $this->name = $inspector->getName();
        $this->location = $inspector->getLocation();
    }
}