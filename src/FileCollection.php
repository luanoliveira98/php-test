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
        if (!$this->has($index)) {
            return $defaultValue;
        }

        if ($this->isExpired($index)) {
            return null;
        }

        return $this->read($index);
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $index, $value, int $expirationTime = 60)
    {
        if ($expirationTime > 0) {
            $expirationTime = time() + $expirationTime;
        }

        $data = '';

        if (is_array($value)) {
            foreach ($value as $key => $val) {
                $data .= $val;

                if ($key !== array_key_last($value)) {
                    $data .= ', ';
                }
            }
        } else {
            $data = $value;
        }

        $dataWrote = $index . ':' . $data . ';' . $expirationTime . '|' . PHP_EOL;
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

    public function isExpired(string $index)
    {
        if (time() <= $this->readTimeExpiration($index)) {
            return false;
        }
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {
        $file = fopen($this->filename, 'r');

        $data = [];
        while (!feof($file)) {
            $row = explode(';', fgets($file));
            $value = explode(':', $row[0]);

            if (isset($value[1])) {
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

    /**
     * {@inheritDoc}
     */
    public function read(string $index)
    {
        $file = fopen($this->filename, 'r');

        while (!feof($file)) {
            $row = explode(';', fgets($file));
            $value = explode(':', $row[0]);
            if ($value[0] == $index) {
                $isArray = explode(',', $value[1]);

                if (isset($isArray[1])) {
                        $value[1] = [];
                    for ($i = 0; $i < count($isArray); $i++) {
                        $value[1][] = $isArray[$i];
                    }
                }
                
                fclose($file);
                return $value[1];
            }
        }
        fclose($file);
        return ;
    }

    /**
     * {@inheritDoc}
     */
    public function readTimeExpiration(string $index)
    {
        $file = fopen($this->filename, 'r');

        while (!feof($file)) {
            $row = explode(';', fgets($file));
            $value = explode(':', $row[0]);

            if ($value[0] == $index) {
                fclose($file);
                $expirationTime = explode('|', $row[1]);
                return $expirationTime[0];
            }
        }

        fclose($file);
        return ;
    }
}
