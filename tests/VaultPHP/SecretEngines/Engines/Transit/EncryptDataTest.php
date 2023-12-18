<?php

namespace Test\VaultPHP\SecretEngines\Engines\Transit;

use Test\VaultPHP\SecretEngines\AbstractSecretEngineTestCase;
use VaultPHP\SecretEngines\Engines\Transit\EncryptionType;
use VaultPHP\SecretEngines\Engines\Transit\Request\EncryptData\EncryptDataRequest;
use VaultPHP\SecretEngines\Engines\Transit\Response\EncryptDataResponse;
use VaultPHP\SecretEngines\Engines\Transit\Transit;

/**
 * Class EncryptDataTest.
 */
final class EncryptDataTest extends AbstractSecretEngineTestCase
{
    public function testApiCall()
    {
        $encryptDataRequest = new EncryptDataRequest(
            'foobar',
            'encryptMe'
        );
        $encryptDataRequest->setContext('fooContext');
        $encryptDataRequest->setNonce('fooNonce');
        $encryptDataRequest->setType(EncryptionType::AES_256_GCM_96);

        $client = $this->createApiClient(
            'POST',
            '/v1/transit/encrypt/foobar',
            $encryptDataRequest->toArray(),
            [
                'data' => [
                    'ciphertext' => 'fooCipher',
                ],
            ]
        );

        $api = new Transit($client);

        $response = $api->encryptData($encryptDataRequest);
        static::assertInstanceOf(EncryptDataResponse::class, $response);
        static::assertSame('fooCipher', $response->getCiphertext());

        static::assertSame('foobar', $encryptDataRequest->getName());
        static::assertSame('fooNonce', $encryptDataRequest->getNonce());
        static::assertSame('fooContext', $encryptDataRequest->getContext());
        static::assertSame(EncryptionType::AES_256_GCM_96, $encryptDataRequest->getType());
        static::assertSame(base64_encode('encryptMe'), $encryptDataRequest->getPlaintext());
    }
}
