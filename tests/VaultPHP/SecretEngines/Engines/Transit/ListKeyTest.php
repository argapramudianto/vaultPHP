<?php

namespace Test\VaultPHP\SecretEngines\Engines\Transit;

use Test\VaultPHP\SecretEngines\AbstractSecretEngineTestCase;
use VaultPHP\SecretEngines\Engines\Transit\Response\ListKeysResponse;
use VaultPHP\SecretEngines\Engines\Transit\Transit;

/**
 * Class ListKeyTest.
 */
final class ListKeyTest extends AbstractSecretEngineTestCase
{
    public function testApiCall()
    {
        $client = $this->createApiClient(
            'LIST',
            '/v1/transit/keys',
            [],
            [
                'data' => [
                    'keys' => [
                        'key1',
                        'key2',
                    ],
                ],
            ]
        );
        $api = new Transit($client);
        $response = $api->listKeys();

        static::assertInstanceOf(ListKeysResponse::class, $response);
        static::assertSame(['key1', 'key2'], $response->getKeys());
    }

    public function testListKeyRequestHasNoData()
    {
        $client = $this->createApiClient(
            'LIST',
            '/v1/transit/keys',
            [],
            [],
            404
        );
        $api = new Transit($client);
        $response = $api->listKeys();

        static::assertInstanceOf(ListKeysResponse::class, $response);
    }
}
