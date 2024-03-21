<?php
declare(strict_types=1);

namespace App\Controller;

use App\Factory\CountRequestFactoryInterface;
use App\Factory\CountResponseFactoryInterface;
use App\Module\Log\Factory\SearchCriteriaFactoryInterface;
use App\Module\Log\Service\LogLinesRetrieverServiceInterface;
use App\Request\Exception\InvalidParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/', name: 'app_test')]
class LogsController extends AbstractController
{
    private const COUNTER_BODY_CTX = 'counter';

    public function __construct(
        private readonly CountRequestFactoryInterface      $countRequestFactory,
        private readonly SearchCriteriaFactoryInterface    $criteriaFactory,
        private readonly LogLinesRetrieverServiceInterface $linesRetrieverService,
        private readonly CountResponseFactoryInterface     $countResponseFactory,
    )
    {
    }

    #[Route(path: '/count', name: 'logs_count', methods: ['GET'])]
    public function count(
        Request $request,
    ): JsonResponse
    {
        try {
            $countRequest = $this->countRequestFactory->get($request);
        } catch (InvalidParameter $e) {
            return $this->json($this->countResponseFactory->getError(
                message: $e->getMessage(),
                parameter: $e->getParameter(),
                parameterValue: $e->getValue(),
            ), Response::HTTP_BAD_REQUEST);
        }
        $criteria = $this->criteriaFactory->getCriteria($countRequest);
        return $this->json($this->countResponseFactory->get(counter: $this->linesRetrieverService->getLinesCount($criteria)), Response::HTTP_OK);
    }
}
