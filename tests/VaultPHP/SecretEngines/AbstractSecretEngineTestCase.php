<?php

namespace Test\VaultPHP\SecretEngines;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use VaultPHP\Authentication\Provider\Token;
use VaultPHP\VaultClient;

/**
 * Class SecretEngineTest.
 */
abstract class AbstractSecretEngineTestCase extends TestCase
{
    protected function createApiClient($expectedMethod, $expectedPath, $expectedData, $responseData, $responseStatus = 200)
    {
        $httpMock = $this->createMock(ClientInterface::class);
        $httpMock
            ->expects(static::once())
            ->method('sendRequest')
            ->with(static::callback(function (RequestInterface $request) use ($expectedMethod, $expectedPath, $expectedData) {
                $this->assertSame($request->getMethod(), $expectedMethod);
                $this->assertSame($request->getUri()->getPath(), $expectedPath);
                $this->assertSame($request->getBody()->getContents(), json_encode($expectedData));

                return true;
            }))
            ->willReturn(new Response($responseStatus, [], json_encode($responseData)));

        return new VaultClient($httpMock, new Token('foo'), 'http://iDontCare.de:443');
    }
}
