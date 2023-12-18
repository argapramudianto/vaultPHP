<?php

namespace VaultPHP\Authentication;

/**
 * Class AuthenticationMetaData.
 */
class AuthenticationMetaData
{
    /** @var string */
    private $token = '';

    /**
     * AuthenticationMetaData constructor.
     *
     * @param object|null $fromAuth
     */
    public function __construct($fromAuth = null)
    {
        if ($fromAuth) {
            /* @var string token */
            $this->token = $fromAuth->client_token;
        }
    }

    /**
     * @return string
     */
    public function getClientToken()
    {
        return $this->token;
    }

    /**
     * @return bool
     */
    public function isClientTokenPresent()
    {
        return (bool) $this->token;
    }
}
