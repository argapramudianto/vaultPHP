<?php

namespace VaultPHP\SecretEngines;

use VaultPHP\VaultClient;

/**
 * Class AbstractSecretEngine.
 */
abstract class AbstractSecretEngine
{
    /** @var VaultClient */
    protected $vaultClient;

    public function __construct(VaultClient $VaultClient)
    {
        $this->vaultClient = $VaultClient;
    }
}
