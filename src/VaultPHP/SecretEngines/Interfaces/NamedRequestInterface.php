<?php

namespace VaultPHP\SecretEngines\Interfaces;

/**
 * Interface NamedRequestInterface.
 */
interface NamedRequestInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);
}
