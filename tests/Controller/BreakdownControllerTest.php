<?php

declare(strict_types=1);


namespace App\Tests\Controller;


use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BreakdownControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
    }

    public function testBreakdownCalculation()
    {
        $this->client->jsonRequest(
            method: Request::METHOD_POST,
            uri: '/api/breakdown',
            parameters: [
                't1'          => '2021-01-01T00:00:00+00:00',
                't2'          => '2022-01-01T00:00:00+00:00',
                'expressions' => [
                    'm',
                    'd',
                    'h',
                    'i',
                    's',
                    '2m',
                    '3d',
                    '10h',
                    '100i',
                    '10000s',
                ],
            ]
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