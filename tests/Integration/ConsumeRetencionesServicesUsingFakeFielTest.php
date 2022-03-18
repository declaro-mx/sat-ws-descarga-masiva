<?php

declare(strict_types=1);

namespace PhpCfdi\SatWsDescargaMasiva\Tests\Integration;

use PhpCfdi\SatWsDescargaMasiva\Services\Query\QueryParameters;
use PhpCfdi\SatWsDescargaMasiva\Shared\DateTimePeriod;
use PhpCfdi\SatWsDescargaMasiva\Shared\DocumentStatus;
use PhpCfdi\SatWsDescargaMasiva\Shared\DownloadType;
use PhpCfdi\SatWsDescargaMasiva\Shared\RequestType;
use PhpCfdi\SatWsDescargaMasiva\Shared\ComplementoRetenciones;
use PhpCfdi\SatWsDescargaMasiva\Shared\RfcMatch;
use PhpCfdi\SatWsDescargaMasiva\Shared\RfcOnBehalf;
use PhpCfdi\SatWsDescargaMasiva\Shared\ServiceEndpoints;
use PhpCfdi\SatWsDescargaMasiva\Shared\Uuid;

/**
 * @todo Parameter RequestType::cfdi() is failing, enable when SAT is working
 * @todo Parameter ComplementoRetenciones is failing, enable when SAT is working
 */
final class ConsumeRetencionesServicesUsingFakeFielTest extends ConsumeServiceTestCase
{
    protected function getServiceEndpoints(): ServiceEndpoints
    {
        return ServiceEndpoints::retenciones();
    }

    public function testQueryChangeAllParameters(): void
    {
        $service = $this->createService();

        $parameters = QueryParameters::create()
            ->withPeriod(DateTimePeriod::createFromValues('2019-01-01 00:00:00', '2019-01-01 00:04:00'))
            ->withDownloadType(DownloadType::received())
            ->withRequestType(RequestType::metadata())
            ->withComplement(ComplementoRetenciones::undefined())
            ->withDocumentStatus(DocumentStatus::active())
            ->withUuid(Uuid::create('96623061-61fe-49de-b298-c7156476aa8b'))
            ->withRfcOnBehalf(RfcOnBehalf::create('XXX01010199A'))
            ->withRfcMatch(RfcMatch::create('AAA010101AAA'))
        ;

        $result = $service->query($parameters);
        $this->assertSame(
            305,
            $result->getStatus()->getCode(),
            'Expected to receive a 305 - Certificado Inválido from SAT since FIEL is for testing'
        );
    }
}
