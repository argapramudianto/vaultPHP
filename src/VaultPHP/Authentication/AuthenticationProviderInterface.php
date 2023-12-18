<?php

namespace VaultPHP\Authentication;

use VaultPHP\Exceptions\VaultException;
use VaultPHP\VaultClient;

/**
 * Interface AuthenticationProviderInterface.
 */
interface AuthenticationProviderInterface
{
    /**
     * @return AuthenticationMetaData|bool
     */
    public function authenticate();

    /**
     * @return void
     */
    public function setVaultClient(VaultClient $VaultClient);

    /**
     * @return VaultClient
     *
     * @throws VaultException
     */
    public function getVaultClient();
}
