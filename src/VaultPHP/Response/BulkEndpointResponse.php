<?php

namespace VaultPHP\Response;

use ArrayAccess;
use Iterator;
use VaultPHP\Exceptions\VaultException;

/**
 * Class BulkEndpointResponse.
 *
 * @template-implements Iterator<int>
 * @template-implements ArrayAccess<int, mixed>
 */
class BulkEndpointResponse extends EndpointResponse implements \Iterator, \ArrayAccess, \Countable
{
    /** @var int */
    private $iteratorPosition = 0;

    /**
     * @var array
     */
    protected $batch_results = [];

    /**
     * @return bool
     */
    public function hasErrors()
    {
        $errorOccurred = $this->getMetaData()->hasErrors();

        if (!$errorOccurred) {
            /** @var EndpointResponse $batchResult */
            foreach ($this as $batchResult) {
                $errorOccurred = $batchResult->getMetaData()->hasErrors();
                if ($errorOccurred) {
                    return true;
                }
            }
        }

        return $errorOccurred;
    }

    /**
     * @return array
     */
    public function getBatchResults()
    {
        return $this->batch_results;
    }

    /**
     * @return int|null
     */
    public function current()
    {
        return $this->batch_results[$this->iteratorPosition];
    }

    /**
     * @return void
     */
    public function next()
    {
        ++$this->iteratorPosition;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->iteratorPosition;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->batch_results[$this->iteratorPosition]);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->iteratorPosition = 0;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->batch_results);
    }

    /**
     * @param int $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->batch_results[$offset]);
    }

    /**
     * @param int $offset
     */
    public function offsetGet($offset)
    {
        return $this->batch_results[$offset];
    }

    /**
     * @throws VaultException
     */
    public function offsetSet($offset, $value)
    {
        throw new VaultException('readonly');
    }

    /**
     * @throws VaultException
     */
    public function offsetUnset($offset)
    {
        throw new VaultException('readonly');
    }
}
