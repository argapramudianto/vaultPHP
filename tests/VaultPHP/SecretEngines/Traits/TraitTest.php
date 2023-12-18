<?php

namespace Test\VaultPHP\SecretEngines\Traits;

use PHPUnit\Framework\TestCase;
use VaultPHP\SecretEngines\Engines\Transit\EncryptionType;
use VaultPHP\SecretEngines\Engines\Transit\Request\CreateKeyRequest;
use VaultPHP\SecretEngines\Engines\Transit\Request\DecryptData\DecryptData;
use VaultPHP\SecretEngines\Engines\Transit\Request\DecryptData\DecryptDataBulkRequest;
use VaultPHP\SecretEngines\Interfaces\ArrayExportInterface;
use VaultPHP\SecretEngines\Interfaces\BulkResourceRequestInterface;
use VaultPHP\SecretEngines\Interfaces\NamedRequestInterface;

/**
 * Class TraitTest.
 */
class TraitTest extends TestCase
{
    public function testArrayExtractionFromRequest()
    {
        $request = new CreateKeyRequest('fooTest');
        $request->setType(EncryptionType::RSA_2048);

        static::assertInstanceOf(ArrayExportInterface::class, $request);

        $expectedArray = [
            'type' => 'rsa-2048',
            'name' => 'fooTest',
        ];
        static::assertSame($expectedArray, $request->toArray());
    }

    public function testNestedArrayExtractionFromRequest()
    {
        $request = new DecryptDataBulkRequest('fooTest');
        $request->addBulkRequests([
            new DecryptData('foo', 'fooContext', 'fooNonce'),
            new DecryptData('foo2', 'fooContext2', 'fooNonce2'),
        ]);

        $request->addBulkRequest(
            new DecryptData('foo3', 'fooContext3', 'fooNonce3')
        );

        static::assertInstanceOf(ArrayExportInterface::class, $request);
        static::assertInstanceOf(BulkResourceRequestInterface::class, $request);
        static::assertInstanceOf(NamedRequestInterface::class, $request);

        $expectedArray = [
            'name' => 'fooTest',
            'batch_input' => [
                [
                    'ciphertext' => 'foo',
                    'context' => 'fooContext',
                    'nonce' => 'fooNonce',
                ],
                [
                    'ciphertext' => 'foo2',
                    'context' => 'fooContext2',
                    'nonce' => 'fooNonce2',
                ],
                [
                    'ciphertext' => 'foo3',
                    'context' => 'fooContext3',
                    'nonce' => 'fooNonce3',
                ],
            ],
        ];
        static::assertSame($expectedArray, $request->toArray());
        static::assertSame('fooTest', $request->getName());
    }
}
