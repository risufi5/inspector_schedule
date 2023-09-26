<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\JobStatusEnum;
use App\Repository\JobRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

#[ORM\Entity(repositoryClass: JobRepository::class)]
class Job
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[JMS\Expose]
    #[JMS\Groups(['jobs'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[JMS\Expose]
    #[JMS\Groups(['jobs'])]
    private string $description;

    #[ORM\Column(length: 255, nullable: false, enumType: JobStatusEnum::class)]
    #[JMS\Expose]
    #[JMS\Groups(['jobs'])]
    private JobStatusEnum $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status->value;
    }

    public function setStatus(JobStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }
}
