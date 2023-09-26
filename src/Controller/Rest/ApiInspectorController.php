<?php

declare(strict_types=1);

namespace App\Controller\Rest;

use App\Dto\Request\AssignJobRequest;
use App\Dto\Request\CompleteJobRequest;
use App\Dto\Response\AssessmentResponse;
use App\Dto\Response\InspectorResponse;
use App\Repository\AssessmentRepository;
use App\Repository\InspectorRepository;
use App\Service\AssessmentService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


#[OA\Tag(name: 'Inspectors')]
#[Rest\Route('/api')]
class ApiInspectorController extends AbstractController
{
    public function __construct(
        private readonly InspectorRepository $inspectorRepository,
        private readonly AssessmentRepository $assessmentRepository,
        private readonly AssessmentService $assessmentService,
    ) {
    }

    #[Rest\Get(
        path: '/inspectors',
        name: 'api_get_inspectors',
        methods: ['GET'],
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'Success.',
        content: new Model(type: InspectorResponse::class, groups: ['inspectors'])
    )]
    public function index()
    {
        $inspectors = $this->inspectorRepository->findAll();

        return new JsonResponse($inspectors, 200, [], true);
    }

    #[Rest\Post(
        path: '/inspectors/{id}/job',
        name: 'api_post_inspectors_job',
        requirements: ['id' => '\d+']
    )]
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
        AssignJobRequest $request
    ): JsonResponse
    {
        $inspector = $this->inspectorRepository->find($id);

        if (!$inspector){
            throw new NotFoundHttpException('The inspector was not found.');
        }
        $assignment = $this->assessmentService->assignJob($inspector, $request);

        return new JsonResponse($assignment, 200, [], true);
    }

    #[Rest\Put(
        path: '/inspectors/{id}/job/{jobId}',
        name: 'api_put_inspectors_job',
        requirements: ['id' => '\d+']
    )]
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
        CompleteJobRequest $request
    ): Response
    {
        $inspector = $this->inspectorRepository->find($id);
        if (!$inspector){
            throw new NotFoundHttpException('The inspector was not found.');
        }

        $assessment = $this->assessmentRepository->findOneBy(['inspector' => $id, 'job' => $jobId]);
        if (!$assessment){
            throw new NotFoundHttpException('The job with id ' . $jobId . ' is not assigned to the inspector.');
        }
        $this->assessmentService->completeJob($assessment, $request);

        return new Response('Job completed.');
    }
}