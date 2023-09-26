<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\AssessmentStatusEnum;
use App\Repository\AssessmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;

#[ORM\Entity(repositoryClass: AssessmentRepository::class)]
class Assessment
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[JMS\Expose]
    #[JMS\Groups(['assessments'])]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[JMS\Expose]
    #[JMS\Groups(['assessments.inspector'])]
    private Inspector $inspector;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[JMS\Expose]
    #[JMS\Groups(['assessments.job'])]
    private Job $job;

    #[ORM\Column(length: 255, nullable: false, enumType: AssessmentStatusEnum::class)]
    #[JMS\Expose]
    #[JMS\Groups(['assessments'])]
    private AssessmentStatusEnum $status;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    #[JMS\Expose]
    #[JMS\Groups(['assessments'])]
    private \DateTimeInterface $assigned_date;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    #[JMS\Expose]
    #[JMS\Groups(['assessments'])]
    private \DateTimeInterface $delivery_date;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[JMS\Expose]
    #[JMS\Groups(['assessments'])]
    private ?string $note = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInspector(): Inspector
    {
        return $this->inspector;
    }

    public function setInspector(Inspector $inspector): static
    {
        $this->inspector = $inspector;

        return $this;
    }

    public function getJob(): Job
    {
        return $this->job;
    }

    public function setJob(Job $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status->value;
    }

    public function setStatus(AssessmentStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getAssignedDate(): \DateTimeInterface
    {
        return $this->assigned_date;
    }

    public function setAssignedDate(\DateTimeInterface $assigned_date): static
    {
        $this->assigned_date = $assigned_date;

        return $this;
    }

    public function getDeliveryDate(): \DateTimeInterface
    {
        return $this->delivery_date;
    }

    public function setDeliveryDate(\DateTimeInterface $delivery_date): static
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): static
    {
        $this->note = $note;

        return $this;
    }
}
