<?php

namespace VaultPHP\Response;

class ApiErrors
{
    public const ENCRYPTION_KEY_NOT_FOUND = [
        'no existing key named .+ could be found',
        'encryption key not found',
    ];
}
