<?php

namespace VaultPHP\SecretEngines\Engines\Transit\Response;

use VaultPHP\Response\EndpointResponse;

/**
 * Class DecryptDataResponse.
 */
final class DecryptDataResponse extends EndpointResponse
{
    /** @var string */
    protected $plaintext = '';

    /**
     * @return string
     */
    public function getPlaintext()
    {
        return base64_decode($this->plaintext);
    }
}
