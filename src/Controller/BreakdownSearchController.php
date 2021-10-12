<?php

namespace App\Controller;

use App\Repository\BreakdownRepository;
use App\Service\Breakdown\BreakdownService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BreakdownSearchController extends AbstractController
{
    public function __construct(
        private ValidatorInterface $validator,
        private BreakdownRepository $repository,
        private BreakdownService $breakdownService,
    )
    {
    }

    #[Route('api/breakdown/{t1}/{t2}', name: 'breakdown_search', methods: ['GET'])]
    public function __invoke(string $t1, string $t2): Response
    {
        $violations = $this->validator->validate($t1, [new Assert\DateTime(BreakdownService::DATETIME_FORMAT)]);
        $violations->addAll($this->validator->validate($t2, [new Assert\DateTime(BreakdownService::DATETIME_FORMAT)]));

        if ($violations->count() > 0) {
            return $this->json([
                'message' => 'Invalid date format',
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->json([
            'breakdowns' => $this->repository->find(
                $this->breakdownService->getDatetimeObject($t1),
                $this->breakdownService->getDatetimeObject($t2),
            ),
        ]);
    }
}
