<?php

namespace Test\VaultPHP;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use VaultPHP\Authentication\AuthenticationProviderInterface;
use VaultPHP\Authentication\Provider\Token;
use VaultPHP\Response\EndpointResponse;
use VaultPHP\VaultClient;

/**
 * Class VaultClientTest.
 */
final class VaultClientTest extends TestCase
{
    use TestHelperTrait;

    public function testAuthProviderGetsClientInjected()
    {
        $auth = new Token('foo');
        $httpClient = $this->createMock(ClientInterface::class);
        $client = new VaultClient($httpClient, $auth, TEST_VAULT_ENDPOINT);

        static::assertSame($client, $auth->getVaultClient());
    }

    public function testRequestWillExtendedWithDefaultVars()
    {
        $auth = new Token('fooToken');

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->expects(static::once())
            ->method('sendRequest')
            ->with(static::callback(function (RequestInterface $requestWithDefaults) {
                // test if values from last request are preserved
                $this->assertSame('LOL', $requestWithDefaults->getMethod());
                $this->assertSame('/i/should/be/preserved', $requestWithDefaults->getUri()->getPath());
                $this->assertSame(json_encode(['dontReplaceMe']), $requestWithDefaults->getBody()->getContents());

                // test default values that should be added
                $this->assertSame('http', $requestWithDefaults->getUri()->getScheme());
                $this->assertSame('foo.bar', $requestWithDefaults->getUri()->getHost());
                $this->assertSame(1337, $requestWithDefaults->getUri()->getPort());

                $this->assertSame('1', $requestWithDefaults->getHeader('X-Vault-Request')[0]);
                $this->assertSame('fooToken', $requestWithDefaults->getHeader('X-Vault-Token')[0]);

                return true;
            }))
            ->willReturn(new Response());

        $client = new VaultClient($httpClient, $auth, 'http://foo.bar:1337');
        $client->sendApiRequest('LOL', '/i/should/be/preserved', EndpointResponse::class, ['dontReplaceMe']);
    }

    public function testSendRequest()
    {
        $response = new Response();

        $httpClient = $this->createMock(ClientInterface::class);
        $httpClient
            ->expects(static::once())
            ->method('sendRequest')
            ->with(static::callback(function ($request) {
                $this->assertInstanceOf(RequestInterface::class, $request);

                return true;
            }))
            ->willReturn($response);

        $auth = $this->createMock(AuthenticationProviderInterface::class);

        $client = new VaultClient($httpClient, $auth, TEST_VAULT_ENDPOINT);
        $client->sendApiRequest('GET', '/foo', EndpointResponse::class, [], false);
    }

    public function testSuccessApiResponse()
    {
        $response = $this->simulateApiResponse(200, '');
        static::assertInstanceOf(EndpointResponse::class, $response);
    }

    public function testEmptyResponse()
    {
        $response = $this->simulateApiResponse(404);
        static::assertInstanceOf(EndpointResponse::class, $response);
    }
}
