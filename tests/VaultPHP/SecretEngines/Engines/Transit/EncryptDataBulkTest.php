<?php

namespace Test\VaultPHP\SecretEngines\Engines\Transit;

use Test\VaultPHP\SecretEngines\AbstractSecretEngineTestCase;
use VaultPHP\SecretEngines\Engines\Transit\Request\EncryptData\EncryptData;
use VaultPHP\SecretEngines\Engines\Transit\Request\EncryptData\EncryptDataBulkRequest;
use VaultPHP\SecretEngines\Engines\Transit\Response\EncryptDataResponse;
use VaultPHP\SecretEngines\Engines\Transit\Transit;

/**
 * Class EncryptDataBulkTest.
 */
final class EncryptDataBulkTest extends AbstractSecretEngineTestCase
{
    public function testApiCall()
    {
        $encryptRequest = new EncryptDataBulkRequest(
            'foobar',
            [
                new EncryptData('foo', 'fooContext', 'fooNonce'),
                new EncryptData('foo2', 'fooContext', 'fooNonce'),
            ]
        );

        $client = $this->createApiClient(
            'POST',
            '/v1/transit/encrypt/foobar',
            $encryptRequest->toArray(),
            [
                'data' => [
                    'batch_results' => [
                        ['ciphertext' => 'foo1'],
                        ['ciphertext' => 'foo2'],
                    ],
                ],
            ]
        );

        $api = new Transit($client);
        $response = $api->encryptDataBulk($encryptRequest);

        static::assertSame(count($response), 2);

        /** @var EncryptDataResponse $bulkResponseOne */
        $bulkResponseOne = $response[0];
        static::assertSame('foo1', $bulkResponseOne->getCiphertext());

        /** @var EncryptDataResponse $bulkResponseTwo */
        $bulkResponseTwo = $response[1];
        static::assertSame('foo2', $bulkResponseTwo->getCiphertext());
    }
}
