<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Doctrine\DBAL\Types\Types;
use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

class CompleteJobRequest
{
    #[Assert\Type(type: Types::STRING)]
    #[Assert\Length(max: 255)]
    #[OA\Property(example: 'Assessment on completed job')]
    protected string $note;

    public function __construct(
        string $note,
    ) {
        $this->note = $note;
    }

    public function getNote(): string
    {
        return $this->note;
    }
}
