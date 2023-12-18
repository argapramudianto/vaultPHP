<?php

namespace Test\VaultPHP\Response;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use VaultPHP\Exceptions\VaultException;
use VaultPHP\Response\BulkEndpointResponse;
use VaultPHP\Response\MetaData;
use VaultPHP\SecretEngines\Engines\Transit\Response\DecryptDataResponse;

/**
 * Class BulkEndpointResponseTest.
 */
final class BulkEndpointResponseTest extends TestCase
{
    public function testCanInteractWithBulkResponseLikeArray()
    {
        $response = new Response(200, [], json_encode([
            'data' => [
                'batch_results' => [
                    [],
                    [],
                    [],
                ],
            ],
        ]));
        $bulkResponses = DecryptDataResponse::fromBulkResponse($response);
        static::assertInstanceOf(BulkEndpointResponse::class, $bulkResponses);
        static::assertTrue(is_array($bulkResponses->getBatchResults()));

        // test foreach
        foreach ($bulkResponses as $batchResponse) {
            static::assertInstanceOf(DecryptDataResponse::class, $batchResponse);
        }

        // can count
        static::assertCount(3, $bulkResponses);

        $bulkResponses->rewind();
        static::assertTrue($bulkResponses->valid());

        // can interact with index
        static::assertInstanceOf(DecryptDataResponse::class, $bulkResponses[2]);

        // can iterate
        static::assertSame($bulkResponses[0], $bulkResponses->current());

        $bulkResponses->next();
        static::assertSame($bulkResponses[1], $bulkResponses->current());

        $bulkResponses->next();
        static::assertSame($bulkResponses[2], $bulkResponses->current());

        $bulkResponses->next();
        static::assertFalse($bulkResponses->valid());

        static::assertSame(3, $bulkResponses->key());
        static::assertTrue(isset($bulkResponses[0]));
        static::assertFalse(isset($bulkResponses[3]));
    }

    public function testCantWriteToArrayStyleObject()
    {
        $this->expectException(VaultException::class);
        $this->expectExceptionMessage('readonly');

        $response = new Response(200, [], json_encode([
            'data' => [
                'batch_results' => [
                    [],
                    [],
                    [],
                ],
            ],
        ]));
        $bulkResponses = DecryptDataResponse::fromBulkResponse($response);
        $bulkResponses[1] = 'foo';
    }

    public function testCantDeleteFromArrayStyleObject()
    {
        $this->expectException(VaultException::class);
        $this->expectExceptionMessage('readonly');

        $response = new Response(200, [], json_encode([
            'data' => [
                'batch_results' => [
                    [],
                    [],
                    [],
                ],
            ],
        ]));
        $bulkResponses = DecryptDataResponse::fromBulkResponse($response);
        unset($bulkResponses[1]);
    }

    public function testHasErrors()
    {
        $response = new Response(200, [], json_encode([
            'errors' => [],
            'data' => [
                'batch_results' => [
                    [],
                ],
            ],
        ]));
        $bulkResponses = DecryptDataResponse::fromBulkResponse($response);
        static::assertFalse($bulkResponses->hasErrors());

        $response = new Response(200, [], json_encode([
            'errors' => [
                'oh no',
            ],
            'data' => [
                'batch_results' => [
                    [],
                ],
            ],
        ]));
        $bulkResponses = DecryptDataResponse::fromBulkResponse($response);
        static::assertTrue($bulkResponses->hasErrors());

        $response = new Response(200, [], json_encode([
            'errors' => [
            ],
            'data' => [
                'batch_results' => [
                    [],
                    [
                        'error' => 'oh no',
                    ],
                    [],
                ],
            ],
        ]));
        $bulkResponses = DecryptDataResponse::fromBulkResponse($response);
        static::assertTrue($bulkResponses->hasErrors());
    }

    public function testGetErrors()
    {
        $response = new Response(200, [], json_encode([
            'errors' => [
                'foo',
                'bar',
            ],
            'data' => [
                'batch_results' => [
                    [],
                    ['error' => 'baz, buz'],
                    [],
                    ['error' => 'bam'],
                ],
            ],
        ]));
        $bulkResponses = DecryptDataResponse::fromBulkResponse($response);
        static::assertSame([], $bulkResponses[0]->getMetaData()->getErrors());
        static::assertSame(['baz', 'buz'], $bulkResponses[1]->getMetaData()->getErrors());
        static::assertSame([], $bulkResponses[2]->getMetaData()->getErrors());
        static::assertSame(['bam'], $bulkResponses[3]->getMetaData()->getErrors());

        static::assertSame(['foo', 'bar'], $bulkResponses->getMetaData()->getErrors());
    }

    public function testCanGetPopulateMetaDataFromBulkResponse()
    {
        $response = new Response(200, [], json_encode([
            'errors' => [
                'metaDataError',
                'metaDataError2',
            ],
            'data' => [
                'batch_results' => [
                    [
                        'error' => 'batchError',
                    ],
                    [],
                    [
                        'error' => 'batchError2',
                    ],
                ],
            ],
        ]));
        $arrayEndpointResponse = DecryptDataResponse::fromBulkResponse($response);
        static::assertSame(3, count($arrayEndpointResponse));

        $basicMeta = $arrayEndpointResponse->getMetaData();
        static::assertSame(['metaDataError', 'metaDataError2'], $basicMeta->getErrors());
        static::assertTrue($arrayEndpointResponse->hasErrors());

        /** @var DecryptDataResponse $batchResponse */
        foreach ($arrayEndpointResponse as $batchResponse) {
            static::assertInstanceOf(DecryptDataResponse::class, $batchResponse);
            static::assertInstanceOf(MetaData::class, $batchResponse->getMetaData());
        }

        static::assertSame(['batchError'], $arrayEndpointResponse[0]->getMetaData()->getErrors());
        static::assertSame([], $arrayEndpointResponse[1]->getMetaData()->getErrors());
        static::assertSame(['batchError2'], $arrayEndpointResponse[2]->getMetaData()->getErrors());
    }

    public function testBulkPayloadWillBePopulatedToResponseClass()
    {
        $batchResponse = [
            ['plaintext' => base64_encode('OH NO')],
            ['plaintext' => base64_encode('WHHAAT')],
        ];

        $response = new Response(200, [], json_encode([
            'data' => [
                'batch_results' => $batchResponse,
            ],
        ]));

        $arrayEndpointResponse = DecryptDataResponse::fromBulkResponse($response);
        foreach ($arrayEndpointResponse as $bulkResponse) {
            $expected = array_map('base64_decode', current($batchResponse));
            static::assertSame(current($expected), $bulkResponse->getPlaintext());
            next($batchResponse);
        }
    }
}
