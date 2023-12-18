<?php

namespace VaultPHP\SecretEngines\Engines\Transit;

use VaultPHP\Exceptions\InvalidDataException;
use VaultPHP\Exceptions\InvalidRouteException;
use VaultPHP\Exceptions\VaultAuthenticationException;
use VaultPHP\Exceptions\VaultException;
use VaultPHP\Exceptions\VaultHttpException;
use VaultPHP\Response\BulkEndpointResponse;
use VaultPHP\Response\EndpointResponse;
use VaultPHP\SecretEngines\AbstractSecretEngine;
use VaultPHP\SecretEngines\Engines\Transit\Request\CreateKeyRequest;
use VaultPHP\SecretEngines\Engines\Transit\Request\DecryptData\DecryptDataBulkRequest;
use VaultPHP\SecretEngines\Engines\Transit\Request\DecryptData\DecryptDataRequest;
use VaultPHP\SecretEngines\Engines\Transit\Request\EncryptData\EncryptDataBulkRequest;
use VaultPHP\SecretEngines\Engines\Transit\Request\EncryptData\EncryptDataRequest;
use VaultPHP\SecretEngines\Engines\Transit\Request\UpdateKeyConfigRequest;
use VaultPHP\SecretEngines\Engines\Transit\Response\CreateKeyResponse;
use VaultPHP\SecretEngines\Engines\Transit\Response\DecryptDataResponse;
use VaultPHP\SecretEngines\Engines\Transit\Response\DeleteKeyResponse;
use VaultPHP\SecretEngines\Engines\Transit\Response\EncryptDataResponse;
use VaultPHP\SecretEngines\Engines\Transit\Response\ListKeysResponse;
use VaultPHP\SecretEngines\Engines\Transit\Response\UpdateKeyConfigResponse;

/**
 * Class Transit.
 */
final class Transit extends AbstractSecretEngine
{
    /**
     * @return CreateKeyResponse
     *
     * @throws InvalidDataException
     * @throws InvalidRouteException
     * @throws VaultException
     */
    public function createKey(CreateKeyRequest $createKeyRequest)
    {
        /* @var CreateKeyResponse */
        return $this->vaultClient->sendApiRequest(
            'POST',
            sprintf('/v1/transit/keys/%s', urlencode($createKeyRequest->getName())),
            CreateKeyResponse::class,
            $createKeyRequest
        );
    }

    /**
     * @return EncryptDataResponse
     *
     * @throws InvalidDataException
     * @throws InvalidRouteException
     * @throws VaultException
     */
    public function encryptData(EncryptDataRequest $encryptDataRequest)
    {
        /* @var EncryptDataResponse */
        return $this->vaultClient->sendApiRequest(
            'POST',
            sprintf('/v1/transit/encrypt/%s', urlencode($encryptDataRequest->getName())),
            EncryptDataResponse::class,
            $encryptDataRequest
        );
    }

    /**
     * @return BulkEndpointResponse
     *
     * @throws InvalidDataException
     * @throws InvalidRouteException
     * @throws VaultException
     */
    public function encryptDataBulk(EncryptDataBulkRequest $encryptDataBulkRequest)
    {
        /* @var BulkEndpointResponse */
        return $this->vaultClient->sendApiRequest(
            'POST',
            sprintf('/v1/transit/encrypt/%s', urlencode($encryptDataBulkRequest->getName())),
            EncryptDataResponse::class,
            $encryptDataBulkRequest
        );
    }

    /**
     * @return DecryptDataResponse
     *
     * @throws InvalidDataException
     * @throws InvalidRouteException
     * @throws VaultException
     */
    public function decryptData(DecryptDataRequest $decryptDataRequest)
    {
        /* @var DecryptDataResponse */
        return $this->vaultClient->sendApiRequest(
            'POST',
            sprintf('/v1/transit/decrypt/%s', urlencode($decryptDataRequest->getName())),
            DecryptDataResponse::class,
            $decryptDataRequest
        );
    }

    /**
     * @return BulkEndpointResponse
     *
     * @throws InvalidDataException
     * @throws InvalidRouteException
     * @throws VaultException
     * @throws VaultAuthenticationException
     * @throws VaultHttpException
     */
    public function decryptDataBulk(DecryptDataBulkRequest $decryptDataBulkRequest)
    {
        /* @var BulkEndpointResponse */
        return $this->vaultClient->sendApiRequest(
            'POST',
            sprintf('/v1/transit/decrypt/%s', urlencode($decryptDataBulkRequest->getName())),
            DecryptDataResponse::class,
            $decryptDataBulkRequest
        );
    }

    /**
     * @return ListKeysResponse
     *
     * @throws InvalidDataException
     * @throws InvalidRouteException
     * @throws VaultException
     */
    public function listKeys()
    {
        /* @var ListKeysResponse */
        return $this->vaultClient->sendApiRequest(
            'LIST',
            '/v1/transit/keys',
            ListKeysResponse::class,
            []
        );
    }

    /**
     * @param string $name
     *
     * @return EndpointResponse
     *
     * @throws InvalidDataException
     * @throws InvalidRouteException
     * @throws VaultException
     */
    public function deleteKey($name)
    {
        /* @var EndpointResponse */
        return $this->vaultClient->sendApiRequest(
            'DELETE',
            sprintf('/v1/transit/keys/%s', urlencode($name)),
            DeleteKeyResponse::class,
            []
        );
    }

    /**
     * @return UpdateKeyConfigResponse
     *
     * @throws InvalidDataException
     * @throws InvalidRouteException
     * @throws VaultException
     */
    public function updateKeyConfig(UpdateKeyConfigRequest $updateKeyConfigRequest)
    {
        /* @var UpdateKeyConfigResponse */
        return $this->vaultClient->sendApiRequest(
            'POST',
            sprintf('/v1/transit/keys/%s/config', urlencode($updateKeyConfigRequest->getName())),
            UpdateKeyConfigResponse::class,
            $updateKeyConfigRequest
        );
    }
}
