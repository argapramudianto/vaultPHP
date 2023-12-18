<?php

namespace Test\VaultPHP\SecretEngines\Engines\Transit;

use Test\VaultPHP\SecretEngines\AbstractSecretEngineTestCase;
use VaultPHP\SecretEngines\Engines\Transit\Response\DeleteKeyResponse;
use VaultPHP\SecretEngines\Engines\Transit\Transit;

/**
 * Class DeleteKeyTest.
 */
final class DeleteKeyTest extends AbstractSecretEngineTestCase
{
    public function testApiCall()
    {
        $client = $this->createApiClient(
            'DELETE',
            '/v1/transit/keys/foobar',
            [],
            []
        );

        $api = new Transit($client);
        $response = $api->deleteKey('foobar');
        static::assertInstanceOf(DeleteKeyResponse::class, $response);
    }
}
