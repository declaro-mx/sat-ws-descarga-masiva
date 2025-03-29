<?php

declare(strict_types=1);

namespace PhpCfdi\SatWsDescargaMasiva\Tests\Integration;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;
use PhpCfdi\SatWsDescargaMasiva\Service;
use PhpCfdi\SatWsDescargaMasiva\Services\Query\QueryParameters;
use PhpCfdi\SatWsDescargaMasiva\Shared\ServiceEndpoints;
use PhpCfdi\SatWsDescargaMasiva\Tests\TestCase;
use PhpCfdi\SatWsDescargaMasiva\WebClient\Exceptions\HttpServerError;
use PhpCfdi\SatWsDescargaMasiva\WebClient\GuzzleWebClient;
use PhpCfdi\SatWsDescargaMasiva\WebClient\WebClientInterface;

abstract class ConsumeServiceTestCase extends TestCase
{
    abstract protected function getServiceEndpoints(): ServiceEndpoints;

    protected function createWebClient(): WebClientInterface
    {
        $guzzleClient = new GuzzleClient([
            RequestOptions::CONNECT_TIMEOUT => 5,
            RequestOptions::TIMEOUT => 30,
        ]);
        return new GuzzleWebClient($guzzleClient);
    }

    protected function createService(): Service
    {
        $requestBuilder = $this->createFielRequestBuilderUsingTestingFiles();
        $webClient = $this->createWebClient();
        return new Service($requestBuilder, $webClient, null, $this->getServiceEndpoints());
    }

    public function testAuthentication(): void
    {
        $service = $this->createService();
        $token = $service->authenticate();
        $this->assertTrue($token->isValid());
    }

    public function testQueryDefaultParameters(): void
    {
        $service = $this->createService();

        $parameters = QueryParameters::create();

        try {
            $result = $service->query($parameters);
        } catch (HttpServerError $exception) {
            $this->markTestSkipped("SAT webservice failing: {$exception->getMessage()}");
        }
        $this->assertSame(
            305,
            $result->getStatus()->getCode(),
            'Expected to receive a 305 - Certificado Inválido from SAT since FIEL is for testing'
        );
    }

    public function testVerify(): void
    {
        $service = $this->createService();

        $requestId = '3edbd462-9fa0-4363-b60f-bac332338028';
        try {
            $result = $service->verify($requestId);
        } catch (HttpServerError $exception) {
            $this->markTestSkipped("SAT webservice failing: {$exception->getMessage()}");
        }
        $this->assertSame(
            305,
            $result->getStatus()->getCode(),
            'Expected to receive a 305 - Certificado Inválido from SAT since FIEL is for testing'
        );
    }

    public function testDownload(): void
    {
        $service = $this->createService();

        $requestId = '4e80345d-917f-40bb-a98f-4a73939343c5_01';
        try {
            $result = $service->download($requestId);
        } catch (HttpServerError $exception) {
            $this->markTestSkipped("SAT webservice failing: {$exception->getMessage()}");
        }
        $this->assertSame(
            305,
            $result->getStatus()->getCode(),
            'Expected to receive a 305 - Certificado Inválido from SAT since FIEL is for testing'
        );
    }
}
