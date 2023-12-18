<?php

namespace VaultPHP\SecretEngines\Engines\Transit\Response;

use VaultPHP\Response\EndpointResponse;

/**
 * Class ListKeysResponse.
 */
final class ListKeysResponse extends EndpointResponse
{
    /** @var string[] */
    protected $keys = [];

    public function getKeys()
    {
        return $this->keys;
    }
}
