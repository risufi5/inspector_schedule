<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Doctrine\DBAL\Types\Types;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class AssignJobRequest
{
    #[Assert\Type(type: Types::INTEGER)]
    #[Assert\Length(max: 255)]
    #[OA\Property(example: 1)]
    protected int $jobId;

    #[Assert\DateTime(format: 'Y-m-d')]
    #[Assert\NotBlank]
    #[OA\Property(example: '2023-12-12')]
    protected string $deliveryDate;

    public function __construct(
        int $jobId,
        string $deliveryDate,
    ) {
        $this->jobId = $jobId;
        $this->deliveryDate = $deliveryDate;
    }

    public function getJobId(): int
    {
        return $this->jobId;
    }

    public function getDeliveryDate(): \DateTimeInterface
    {
        return \DateTime::createFromFormat('Y-m-d', $this->deliveryDate);
    }
}
