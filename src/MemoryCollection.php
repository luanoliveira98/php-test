<?php

namespace Live\Collection;

/**
 * Memory collection
 *
 * @package Live\Collection
 */
class MemoryCollection implements CollectionInterface
{
    /**
     * Collection data
     *
     * @var array
     */
    protected $data;

    /**
     * Collection expirationTime
     * 
     * @var array
     */
    protected $expirationTime = [];

    /**
     * Collection defaultExpirationTime
     * 
     * @var int
     */
    protected $defaultExpirationTime = 60;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = [];
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $index, $defaultValue = null)
    {
        if (!$this->has($index)) {
            return $defaultValue;
        }

        return $this->data[$index];
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $index, $value, $expirationTime = null)
    {
        $this->data[$index] = $value;

        if ($expirationTime == null) {
            $this->expirationTime[$index] = time() + $this->defaultExpirationTime;
        } else {
            $this->expirationTime[$index] = time() + $expirationTime;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $index)
    {
        return array_key_exists($index, $this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function isExpired(string $index)
    {
        if(array_key_exists($index, $this->expirationTime)) {
            if(time() <= $this->expirationTime[$index]) {
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * {@inheritDoc}
     */
    public function clean()
    {
        $this->data = [];
    }
}
