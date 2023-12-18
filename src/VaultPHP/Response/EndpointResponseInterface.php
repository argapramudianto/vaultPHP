<?php

namespace VaultPHP\Response;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface EndpointResponseInterface.
 */
interface EndpointResponseInterface
{
    /**
     * @return static
     */
    public static function fromResponse(ResponseInterface $response);

    /**
     * @return BulkEndpointResponse
     */
    public static function fromBulkResponse(ResponseInterface $response);

    /**
     * @return MetaData
     */
    public function getMetaData();

    /**
     * @return bool
     */
    public function hasErrors();
}
