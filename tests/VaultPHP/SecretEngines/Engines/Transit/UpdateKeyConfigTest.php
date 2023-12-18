<?php

namespace Test\VaultPHP\SecretEngines\Engines\Transit;

use Test\VaultPHP\SecretEngines\AbstractSecretEngineTestCase;
use VaultPHP\SecretEngines\Engines\Transit\Request\UpdateKeyConfigRequest;
use VaultPHP\SecretEngines\Engines\Transit\Response\UpdateKeyConfigResponse;
use VaultPHP\SecretEngines\Engines\Transit\Transit;

/**
 * Class UpdateKeyConfigTest.
 */
final class UpdateKeyConfigTest extends AbstractSecretEngineTestCase
{
    public function testApiCall()
    {
        $request = new UpdateKeyConfigRequest('foo');
        $request->setDeletionAllowed(true);
        $request->setExportable(true);
        $request->setAllowPlaintextBackup(true);
        $request->setMinDecryptionVersion(1337);
        $request->setMinEncryptionVersion(1338);

        $client = $this->createApiClient(
            'POST',
            '/v1/transit/keys/foo/config',
            $request->toArray(),
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

        $response = $api->updateKeyConfig($request);
        static::assertInstanceOf(UpdateKeyConfigResponse::class, $response);

        static::assertSame('foo', $request->getName());
        static::assertTrue($request->getDeletionAllowed());
        static::assertTrue($request->getExportable());
        static::assertTrue($request->getAllowPlaintextBackup());
        static::assertSame(1337, $request->getMinDecryptionVersion());
        static::assertSame(1338, $request->getMinEncryptionVersion());
    }
}
