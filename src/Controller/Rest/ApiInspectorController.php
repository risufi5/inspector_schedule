<?php

declare(strict_types=1);

namespace App\Controller\Rest;

use App\Dto\Request\AssignJobRequest;
use App\Dto\Request\CompleteJobRequest;
use App\Dto\Response\AssessmentResponse;
use App\Dto\Response\InspectorResponse;
use App\Repository\InspectorRepository;
use App\Repository\JobRepository;
use App\Service\AssessmentService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

#[OA\Tag(name: 'Inspectors')]
class ApiInspectorController extends AbstractController
{
    public function __construct(
        private readonly InspectorRepository $inspectorRepository,
        private readonly JobRepository $jobRepository,
        private readonly AssessmentService $assessmentService,
    ) {
    }

    #[Rest\Get(
        path: '/api/inspectors',
        name: 'api_get_inspectors',
        methods: ['GET'],
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Success.',
        content: new Model(type: InspectorResponse::class, groups: ['inspectors'])
    )]
    public function index(): JsonResponse
    {
        $inspectors = $this->inspectorRepository->findAll();
        $responseData = [];
        if ($inspectors) {
            foreach ($inspectors as $inspector){
                $responseData[] = [
                    'id' => $inspector->getId(),
                    'name' => $inspector->getName(),
                    'location' => $inspector->getLocation(),
                ];
            }
        }
        return new JsonResponse($responseData, 200);
    }

    #[Rest\Post(
        path: '/api/inspectors/{id}/job',
        name: 'api_post_inspectors_job',
        requirements: ['id' => '\d+']
    )]
    #[ParamConverter('assignJobRequest', converter: 'fos_rest.request_body')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: AssignJobRequest::class)
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Success.',
        content: new Model(type: AssessmentResponse::class, groups: [
            'assessments',
            'assessment.inspector',
            'inspectors',
            'assessment.job',
            'jobs',
        ])
    )]
    #[OA\Response(
        ref: '#/components/responses/UnprocessableEntity',
        response: Response::HTTP_UNPROCESSABLE_ENTITY
    )]
    public function assignJob(
        int $id,
        AssignJobRequest $assignJobRequest
    ): JsonResponse
    {
        $inspector = $this->inspectorRepository->find($id);
        if (!$inspector){
            throw new NotFoundHttpException('The inspector was not found.');
        }

        $assessmentDto = $this->assessmentService->assignJob($inspector, $assignJobRequest);

        return new JsonResponse($assessmentDto, 200);
    }

    #[Rest\Put(
        path: '/api/inspectors/{id}/job/{jobId}',
        name: 'api_put_inspectors_job',
        requirements: ['id' => '\d+']
    )]
    #[ParamConverter('completeJobRequest', converter: 'fos_rest.request_body')]
    #[OA\RequestBody(
        required: true,
        content: new Model(type: CompleteJobRequest::class)
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Success.'
    )]
    #[OA\Response(
        ref: '#/components/responses/UnprocessableEntity',
        response: Response::HTTP_UNPROCESSABLE_ENTITY
    )]
    public function completeJob(
        int $id,
        int $jobId,
        CompleteJobRequest $completeJobRequest
    ): Response
    {
        $inspector = $this->inspectorRepository->find($id);
        if (!$inspector){
            throw new NotFoundHttpException('The inspector was not found.');
        }

        $job = $this->jobRepository->find($jobId);
        if (!$job){
            throw new NotFoundHttpException('The job was not found.');
        }

        $assessmentDto = $this->assessmentService->completeJob($inspector, $job, $completeJobRequest);

        return new JsonResponse($assessmentDto, 200);
    }
}