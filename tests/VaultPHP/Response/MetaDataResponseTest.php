<?php

namespace Test\VaultPHP\Response;

use PHPUnit\Framework\TestCase;
use VaultPHP\Response\ApiErrors;
use VaultPHP\Response\MetaData;

/**
 * Class BasicMetaResponseTest.
 */
final class MetaDataResponseTest extends TestCase
{
    private function createTestData()
    {
        $reflectionClass = new \ReflectionClass(MetaData::class);

        $classPropertyNames = array_map(function ($property) {
            return $property->getName();
        }, $reflectionClass->getProperties());

        return array_combine(
            $classPropertyNames,
            array_map('md5', $classPropertyNames)
        );
    }

    private function checkDtoData($testData, $basicMetaData)
    {
        static::assertSame($testData['errors'], $basicMetaData->getErrors());
        static::assertSame(false, $basicMetaData->hasErrors());
        static::assertSame($testData['lease_duration'], $basicMetaData->getLeaseDuration());
        static::assertSame($testData['auth'], $basicMetaData->getAuth());
        static::assertSame($testData['lease_id'], $basicMetaData->getLeaseId());
        static::assertSame($testData['renewable'], $basicMetaData->getRenewable());
        static::assertSame($testData['request_id'], $basicMetaData->getRequestId());
        static::assertSame($testData['warnings'], $basicMetaData->getWarnings());
        static::assertSame($testData['wrap_info'], $basicMetaData->getWrapInfo());
    }

    public function testCanPopulateArrayDataToSelf()
    {
        $testData = $this->createTestData();
        $basicMetaData = new MetaData((array) $testData);
        $this->checkDtoData($testData, $basicMetaData);
    }

    public function testCanPopulateObjectDataToSelf()
    {
        $testData = $this->createTestData();
        $basicMetaData = new MetaData((object) $testData);
        $this->checkDtoData($testData, $basicMetaData);
    }

    public function testCheckForErrors()
    {
        $error = ['nO eXiStiNg kEy nAMed FOOOBAR cOULd bE foUnD'];
        $basicMetaData = new MetaData(['errors' => $error]);

        static::assertTrue($basicMetaData->hasErrors());
        static::assertSame($error, $basicMetaData->getErrors());
        static::assertTrue($basicMetaData->containsError(ApiErrors::ENCRYPTION_KEY_NOT_FOUND));

        $basicMetaData = new MetaData(['errors' => []]);

        static::assertFalse($basicMetaData->hasErrors());
        static::assertSame([], $basicMetaData->getErrors());
        static::assertFalse($basicMetaData->containsError(ApiErrors::ENCRYPTION_KEY_NOT_FOUND));
    }
}
