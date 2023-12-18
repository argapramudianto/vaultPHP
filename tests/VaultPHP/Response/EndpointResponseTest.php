<?php

namespace Test\VaultPHP\Response;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use VaultPHP\Response\EndpointResponse;
use VaultPHP\Response\MetaData;
use VaultPHP\SecretEngines\Engines\Transit\Response\DecryptDataResponse;

/**
 * Class EndpointResponseTest.
 */
final class EndpointResponseTest extends TestCase
{
    public function testCanGetPopulateMetaDataFromResponse()
    {
        $testMeta = [
            'request_id' => 1337,
            'lease_id' => 1338,
            'renewable' => true,
            'lease_duration' => 1339,
            'wrap_info' => 'foo',
            'warnings' => [
                'fooWarning',
                'fooWarning2',
            ],
            'auth' => [
                'token' => 'fooToken',
            ],
            'errors' => [
                'metaDataError',
                'metaDataError2',
            ],
        ];
        $response = new Response(200, [], json_encode($testMeta));
        $endpointResponse = EndpointResponse::fromResponse($response);
        $basicMeta = $endpointResponse->getMetaData();

        static::assertInstanceOf(EndpointResponse::class, $endpointResponse);
        static::assertInstanceOf(MetaData::class, $basicMeta);

        static::assertSame($testMeta['request_id'], $basicMeta->getRequestId());
        static::assertSame($testMeta['lease_id'], $basicMeta->getLeaseId());
        static::assertSame($testMeta['renewable'], $basicMeta->getRenewable());
        static::assertSame($testMeta['lease_duration'], $basicMeta->getLeaseDuration());
        static::assertSame($testMeta['wrap_info'], $basicMeta->getWrapInfo());
        static::assertSame($testMeta['warnings'], $basicMeta->getWarnings());
        static::assertSame((object) $testMeta['auth'], $basicMeta->getAuth());
        static::assertSame($testMeta['errors'], $basicMeta->getErrors());
    }

    public function testCanGetPopulatePayloadDataFromResponse()
    {
        $response = new Response(200, [], json_encode([
            'data' => [
                'plaintext' => base64_encode('fooPlaintext'),
            ],
        ]));
        $endpointResponse = DecryptDataResponse::fromResponse($response);

        static::assertInstanceOf(EndpointResponse::class, $endpointResponse);
        static::assertSame('fooPlaintext', $endpointResponse->getPlaintext());
    }

    public function testHasErrors()
    {
        $response = new Response(200, [], json_encode([
            'errors' => [],
            'data' => [],
        ]));
        $endpointResponse = DecryptDataResponse::fromResponse($response);
        static::assertFalse($endpointResponse->hasErrors());

        $response = new Response(200, [], json_encode([
            'errors' => [
                'foo',
                'bar',
            ],
            'data' => [],
        ]));
        $endpointResponse = DecryptDataResponse::fromResponse($response);
        static::assertTrue($endpointResponse->hasErrors());
    }

    public function testGetErrors()
    {
        $response = new Response(200, [], json_encode([
            'errors' => [
                'foo',
                'bar',
            ],
            'data' => [],
        ]));
        $endpointResponse = DecryptDataResponse::fromResponse($response);
        static::assertSame(['foo', 'bar'], $endpointResponse->getMetaData()->getErrors());
    }
}
