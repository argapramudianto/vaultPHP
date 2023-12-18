<?php

namespace Test\VaultPHP\SecretEngines\Engines\Transit;

use Test\VaultPHP\SecretEngines\AbstractSecretEngineTestCase;
use VaultPHP\SecretEngines\Engines\Transit\Request\DecryptData\DecryptDataRequest;
use VaultPHP\SecretEngines\Engines\Transit\Response\DecryptDataResponse;
use VaultPHP\SecretEngines\Engines\Transit\Transit;

/**
 * Class DecryptDataTest.
 */
final class DecryptDataTest extends AbstractSecretEngineTestCase
{
    public function testApiCall()
    {
        $decryptDataRequest = new DecryptDataRequest('fooName', 'fooCipher');
        $decryptDataRequest->setNonce('fooNonce');
        $decryptDataRequest->setContext('fooContext');

        $client = $this->createApiClient(
            'POST',
            '/v1/transit/decrypt/fooName',
            $decryptDataRequest->toArray(),
            [
                'data' => [
                    'plaintext' => base64_encode('fooBar'),
                ],
            ]
        );

        $api = new Transit($client);
        $response = $api->decryptData($decryptDataRequest);

        static::assertInstanceOf(DecryptDataResponse::class, $response);
        static::assertSame('fooBar', $response->getPlaintext());

        static::assertSame('fooName', $decryptDataRequest->getName());
        static::assertSame('fooContext', $decryptDataRequest->getContext());
        static::assertSame('fooNonce', $decryptDataRequest->getNonce());
        static::assertSame('fooCipher', $decryptDataRequest->getCiphertext());
    }
}
