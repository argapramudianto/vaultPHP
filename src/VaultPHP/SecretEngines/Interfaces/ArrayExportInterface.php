<?php

namespace VaultPHP\SecretEngines\Interfaces;

/**
 * Interface ArrayExportInterface.
 */
interface ArrayExportInterface
{
    /**
     * @return array
     */
    public function toArray();
}
