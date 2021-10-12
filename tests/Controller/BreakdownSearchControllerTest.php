<?php

declare(strict_types=1);


namespace App\Tests\Controller;


use App\Repository\BreakdownRepository;
use App\Service\Breakdown\BreakdownService;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BreakdownSearchControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    private ?BreakdownRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();

        $this->repository = self::getContainer()->get(BreakdownRepository::class);
    }

    public function testBreakdownSearch()
    {
        $this->repository->persist(
            \DateTimeImmutable::createFromFormat(BreakdownService::DATETIME_FORMAT, '2021-01-01T00:00:00+00:00'),
            \DateTimeImmutable::createFromFormat(BreakdownService::DATETIME_FORMAT, '2022-01-01T00:00:00+00:00'),
            [
                'm'      => 12,
                'd'      => 365,
                'h'      => 8760,
                'i'      => 525600,
                's'      => 31536000,
                '2m'     => 6,
                '3d'     => 121.67,
                '10h'    => 876,
                '100i'   => 5256,
                '10000s' => 3153.6,
            ]
        );

        $this->client->request(
            method: Request::METHOD_GET,
            uri: '/api/breakdown/2021-01-01T00:00:00+00:00/2022-01-01T00:00:00+00:00',
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = $this->client->getResponse();

        $content = json_decode($response->getContent(), true);

        $this->assertSame(['breakdowns' => [
            'm'      => 12,
            'd'      => 365,
            'h'      => 8760,
            'i'      => 525600,
            's'      => 31536000,
            '2m'     => 6,
            '3d'     => 121.67,
            '10h'    => 876,
            '100i'   => 5256,
            '10000s' => 3153.6,
        ]], $content);
    }
}