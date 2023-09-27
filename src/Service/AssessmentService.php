<?php

namespace App\Service;

use App\Dto\Request\AssignJobRequest;
use App\Dto\Request\CompleteJobRequest;
use App\Entity\Assessment;
use App\Entity\Inspector;
use App\Entity\Job;
use App\Enum\AssessmentStatusEnum;
use App\Enum\InspectorLocationEnum;
use App\Enum\JobStatusEnum;
use App\Repository\AssessmentRepository;
use App\Repository\JobRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AssessmentService
{
    public function __construct(
        private readonly AssessmentRepository $assessmentRepository,
        private readonly JobRepository $jobRepository,
        private readonly LoggerInterface $logger,
    )
    {
    }

    public function assignJob(
        Inspector $inspector,
        AssignJobRequest $request,
    ): array
    {
        $this->logger->info('Start process to assign a new job to inspector with id '. $inspector->getId() .'.');

        $assessment = new Assessment();
        $job = $this->jobRepository->find($request->getJobId());
        if (!$job) {
            throw new NotFoundHttpException('Job not found for id '. $request->getJobId() .'.');
        }
        if ($job->getStatus() == JobStatusEnum::ASSIGNED->value) {
            throw new NotFoundHttpException('Job with id '. $request->getJobId() .' is already assigned.');
        }
        $currentTime = new \DateTime();
        $currentTime->format('Y-m-d H:i:s');
        $timezone = $this->setTimezone($inspector, $currentTime);
        $assessment
            ->setInspector($inspector)
            ->setJob($job)
            ->setAssignedDate($timezone)
            ->setDeliveryDate($request->getDeliveryDate())
            ->setStatus(AssessmentStatusEnum::IN_PROGRESS)
            ->setCreatedAt($currentTime)
            ->setUpdatedAt($currentTime);
        $this->assessmentRepository->save($assessment, true);

        $job->setStatus(JobStatusEnum::ASSIGNED);
        $this->jobRepository->save($job, true);

        $assessmentDto[] = [
            'id' => $assessment->getId(),
            'inspectorId' => $assessment->getInspector()->getId(),
            'jobId' => $assessment->getJob()->getId(),
            'status' => $assessment->getStatus(),
            'assigned_date' => $assessment->getAssignedDate()->format('Y-m-d H:i:s'),
            'delivery_date' => $assessment->getDeliveryDate()->format('Y-m-d H:i:s'),
        ];

        return $assessmentDto;
    }

    public function completeJob(
        Inspector $inspector,
        Job  $job,
        CompleteJobRequest $request
    ): array
    {
        $this->logger->info('Start process to complete a job from inspector with id '. $inspector->getId() .'.');

        $assessment = $this->assessmentRepository->findOneBy(['inspector' => $inspector->getId(), 'job' => $job->getId()]);
        if (!$assessment){
            throw new NotFoundHttpException('The job with id ' . $job->getId() . ' is not assigned to the inspector.');
        }
        $currentTime = new \DateTime();
        $currentTime->format('Y-m-d H:i:s');
        $assessment
            ->setNote($request->getNote())
            ->setStatus(AssessmentStatusEnum::COMPLETED)
            ->setUpdatedAt($currentTime);
        $this->assessmentRepository->save($assessment, true);

        $assessmentDto[] = [
            'id' => $assessment->getId(),
            'inspectorId' => $assessment->getInspector()->getId(),
            'jobId' => $assessment->getJob()->getId(),
            'status' => $assessment->getStatus(),
            'assigned_date' => $assessment->getAssignedDate()->format('Y-m-d H:i:s'),
            'delivery_date' => $assessment->getDeliveryDate()->format('Y-m-d H:i:s'),
            'note' => $assessment->getNote(),
        ];
        return $assessmentDto;
    }

    private function setTimezone(
        Inspector $inspector,
        \DateTime $assignedTime
    ): \DateTime
    {
        $timezone = new \DateTime();
        $timezone->format('Y-m-d H:i:s');
        $location = $inspector->getLocation();
        if ($location == InspectorLocationEnum::MADRID->value) {
            $timezone = $assignedTime->modify('+2 hours');
        } else if ($location == InspectorLocationEnum::MEXICO_CITY->value) {
            $timezone = $assignedTime->modify('-6 hours');
        } else if ($location == InspectorLocationEnum::UK->value) {
            $timezone = $assignedTime->modify('+1 hours');
        }
        return $timezone;
    }
}