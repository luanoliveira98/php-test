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

        return $this->read($index);
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $index, $value)
    {
        $data = '';

        if(is_array($value)) {
            foreach ($value as $key => $val) {
                $data .= $val;

                if($key !== array_key_last($value)) {
                    $data .= ', ';
                }
            }
        } else {
            $data = $value;
        }

        $dataWrote = $index . ':' . $data . ';' . PHP_EOL;
        return fwrite($this->file, $dataWrote);

    }

    /**
     * {@inheritDoc}
     */
    public function has(string $index)
    {
        $exists = $this->read($index);
        return ($exists)? true : false;
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        $file = fopen($this->filename, 'r');

        $data = [];
        while(!feof($file)) {
            $row = explode(';', fgets($file));
            $value = explode(':', $row[0]);

            if(isset($value[1])) {
                $data[] = $value[1];
            }
        }

        fclose($file);
        return count($data);
    }

    /**
     * {@inheritDoc}
     */
    public function clean()
    {
        new FileCollection();
    }

    public function read(string $index)
    {
        $file = fopen($this->filename, 'r');

        while(!feof($file)) {
            $row = explode(';', fgets($file));
            $value = explode(':', $row[0]);
            if($value[0] == $index) {

                $isArray = explode(',', $value[1]);

                if(isset($isArray[1])) {
                        $value[1] = [];
                    for($i = 0; $i < count($isArray); $i++) {
                        $value[1][] = $isArray[$i];
                    }
                }
                
                fclose($file);
                return $value[1];
                break;
            }
        }
        fclose($file);
        return ;
    }
}