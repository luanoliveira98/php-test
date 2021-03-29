<?php

namespace Live\Collection;

/**
 * File collection
 * 
 * @package Live\Collection
 */
class FileCollection implements CollectionInterface
{
    /**
     * File name
     * 
     * @var string
     */
    protected $filename = 'files/example.txt';

    /**
     * Collection data
     *
     * @var array
     */
    protected $data;

    /**
     * File
     * 
     * @var false|resource
     */
    protected $file;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->data = [];
        $this->file = fopen($this->filename, 'w+');
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $index, $defaultValue = null)
    {
        if(!$this->has($index)) {
            return $defaultValue;
        }

        return $this->data[$index];
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $index, $value)
    {
        $this->data[$index] = $value;
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
