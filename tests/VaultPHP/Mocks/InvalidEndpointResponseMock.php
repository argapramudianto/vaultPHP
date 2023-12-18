<?php

namespace Test\VaultPHP\Mocks;

use Psr\Http\Message\ResponseInterface;
use VaultPHP\Response\EndpointResponse;

/**
 * Class InvalidEndpointResponseMock.
 */
class InvalidEndpointResponseMock extends EndpointResponse
{
    public static function fromResponse(ResponseInterface $response)
    {
        return 'IamInvalid';
    }

    public function getMetaData()
    {
        return false;
    }

    public function hasErrors()
    {
        return true;
    }
}
