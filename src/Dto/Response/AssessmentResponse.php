<?php

declare(strict_types=1);

namespace App\Dto\Response;

use App\Entity\Assessment;
use App\Entity\Inspector;
use App\Entity\Job;
use JMS\Serializer\Annotation as JMS;
use OpenApi\Attributes as OA;

class AssessmentResponse
{
    #[JMS\Expose]
    #[JMS\Groups(['assessments'])]
    #[OA\Property(example: 1)]
    protected ?int $id;

    #[JMS\Expose]
    #[JMS\Groups(['assessments.inspector'])]
    #[OA\Property(example: [['id' => 2, 'name' => 'Ted', 'location' => 'madrid']])]
    protected ?InspectorResponse $inspector;

    #[JMS\Expose]
    #[JMS\Groups(['assessments.job'])]
    #[OA\Property(example: [['id' => 1, 'description' => 'Work description', 'status' => 'new']])]
    protected ?JobResponse $job;

    #[JMS\Expose]
    #[JMS\Groups(['assessments'])]
    #[OA\Property(example: '2022-10-09')]
    protected ?\DateTimeInterface $assignedDate;

    #[JMS\Expose]
    #[JMS\Groups(['assessments'])]
    #[OA\Property(example: '2022-10-09')]
    protected ?\DateTimeInterface $deliveryDate;

    #[JMS\Expose]
    #[JMS\Groups(['assessments'])]
    #[OA\Property(example: 'Note about the job when finished.')]
    protected ?string $note;

    public function __construct(Assessment $assessment)
    {
        $this->id = $assessment->getId();
        $this->assignedDate = $assessment->getAssignedDate();
        $this->deliveryDate = $assessment->getDeliveryDate();
        $this->note = $assessment->getNote();

        $this->setInspector($assessment->getInspector());
        $this->setJob($assessment->getJob());
    }

    public function setInspector(?Inspector $inspector): self
    {
        $this->inspector = $inspector ? new InspectorResponse($inspector) : null;

        return $this;
    }

    public function setJob(?Job $job): self
    {
        $this->job = $job ? new JobResponse($job) : null;

        return $this;
    }
}