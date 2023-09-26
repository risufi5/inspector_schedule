<?php

namespace App\Service;

use App\Dto\Request\AssignJobRequest;
use App\Dto\Request\CompleteJobRequest;
use App\Entity\Assessment;
use App\Entity\Inspector;
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
    ): Assessment {
        $this->logger->info('Start process to assign a new job to inspector with id '. $inspector->getId() .'.');

        $assessment = new Assessment();
        $job = $this->jobRepository->find($request->getJobId());
        if (!$job) {
            throw new NotFoundHttpException('Job not found for id '. $request->getJobId() .'.');
        }
        if ($job->getStatus() == JobStatusEnum::ASSIGNED) {
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

        return $assessment;
    }

    public function completeJob(
        Assessment $assessment,
        CompleteJobRequest $request
    ): void {
        $this->logger->info('Start process to complete a job from inspector with id '. $assessment->getInspector()->getId() .'.');

        $currentTime = new \DateTime();
        $currentTime->format('Y-m-d H:i:s');
        $assessment
            ->setNote($request->getNote())
            ->setStatus(AssessmentStatusEnum::COMPLETED)
            ->setUpdatedAt($currentTime);
        $this->assessmentRepository->save($assessment, true);
    }

    private function setTimezone(
        Inspector $inspector,
        \DateTime $assignedTime
    ): \DateTime
    {
        $location = $inspector->getLocation();
        if ($location == InspectorLocationEnum::MADRID) {
            $timezone = $assignedTime->modify('+2 hours');
        } else if ($location == InspectorLocationEnum::MEXICO_CITY) {
            $timezone = $assignedTime->modify('-6 hours');
        } else if ($location == InspectorLocationEnum::UK) {
            $timezone = $assignedTime->modify('+1 hours');
        }
        $timezone->format('Y-m-d H:i:s');

        return $timezone;
    }
}