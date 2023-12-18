<?php

namespace Test\VaultPHP\Authentication\Provider;

use PHPUnit\Framework\TestCase;
use VaultPHP\Authentication\AuthenticationMetaData;
use VaultPHP\Authentication\Provider\Token;

/**
 * Class TokenTest.
 */
final class TokenTest extends TestCase
{
    public function testGetToken()
    {
        $tokenAuth = new Token('foobar');
        $tokenMeta = $tokenAuth->authenticate();

        static::assertInstanceOf(AuthenticationMetaData::class, $tokenMeta);
        static::assertSame('foobar', $tokenMeta->getClientToken());
    }
}
