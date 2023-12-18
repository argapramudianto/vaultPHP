<?php

namespace VaultPHP\SecretEngines\Engines\Transit;

/**
 * Class EncryptionType.
 */
abstract class EncryptionType
{
    public const AES_128_GCM_96 = 'aes128-gcm96';
    public const AES_256_GCM_96 = 'aes256-gcm96';
    public const CHA_CHA_20_POLY_1305 = 'chacha20-poly1305';
    public const ED_25519 = 'ed25519';
    public const ECDSA_P256 = 'ecdsa-p256';
    public const ECDSA_P384 = 'ecdsa-p384';
    public const ECDSA_P521 = 'ecdsa-p521';
    public const RSA_2048 = 'rsa-2048';
    public const RSA_3072 = 'rsa-3072';
    public const RSA_4096 = 'rsa-4096';
}
