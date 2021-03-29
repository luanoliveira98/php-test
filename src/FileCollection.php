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
        $data = '';

        if(is_array($value)) {
            $data .= '[';

            foreach ($value as $key => $val) {
                $data .= $val;

                if($key !== array_key_last($value)) {
                    $data .= ', ';
                }
            }

            $data .= ']';
        } else {
            $data = $value;
        }

        $dataWrote = $index . ' -> ' . $data . PHP_EOL;
        return fwrite($this->file, $dataWrote);

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
