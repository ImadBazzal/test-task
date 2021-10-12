<?php

namespace App\Controller;

use App\Service\Breakdown\BreakdownService;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

class BreakdownController extends AbstractController
{
    public function __construct(
        private ValidatorInterface $validator,
        private BreakdownService $breakdownService,
    )
    {
    }

    #[Route('api/breakdown', name: 'breakdown', methods: ['POST'])]
    public function __invoke(Request $request): Response
    {
        try {
            $json = json_decode($request->getContent(), true, flags: JSON_THROW_ON_ERROR);

            $violations = $this->validator->validate($json, [new Assert\Collection([
                't1'          => [new Assert\NotBlank(), new Assert\DateTime(BreakdownService::DATETIME_FORMAT)],
                't2'          => [new Assert\NotBlank(), new Assert\DateTime(BreakdownService::DATETIME_FORMAT)],
                'expressions' => [new Assert\NotBlank()],
            ])]);

            if ($violations->count() > 0) {
                return $this->json([
                    'message'    => 'Invalid request',
                    'violations' => $violations,
                ], Response::HTTP_BAD_REQUEST);
            }

            $breakdowns = $this->breakdownService->getBreakdowns($json['t1'], $json['t2'], $json['expressions']);

            return $this->json([
                'breakdowns' => $breakdowns,
            ]);

        } catch (JsonException) {
            return $this->json([
                'message' => 'Only application/json supported',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable $e) {
            return $this->json([
                'message' => 'Internal error',
                'error'   => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

    }
}
