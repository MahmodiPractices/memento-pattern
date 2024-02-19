<?php

/**
 * Uses for take snapshot for any class
 */

namespace App\Factory;

use Exception;

class Memento
{
    /**
     * Originator definition in export string
     */
    private const ORIGINATOR_DEFINITION_PREFIX_IN_EXPORT = '@originator=';

    /**
     * Defines encrypt algorithm
     */
    private const ENCRYPT_ALGORITHM = 'aes-256-cbc';

    /**
     * Defines encrypt key
     */
    private const ENCRYPT_KEY = "'0123456789abcdef0123456789abcdef'";

    /**
     * Defines encrypt IV
     */
    private const ENCRYPT_IV = "0123456789abcdef";

    /**
     * Keep class name of originator
     *
     * It will set with auto identify
     *
     * @var string
     */
    private string $originator;

    /**
     * All passed data
     *
     * @var array
     */
    private array $data;


    /**
     * Creates new Memento instance.
     *
     * @param string|null $originator The originator class name.
     * @throws Exception
     */
    public function __construct(?string $originator = null)
    {
        $this->originator = $originator ?? $this->autoIdentifyOriginator();
    }

    /**
     * Method caller magic method
     *
     * Uses for authorize object caller and throw exception if it is not originator.
     *
     * @param string $name
     * @param array $arguments
     * @return void
     * @throws Exception
     */
    public function __call(string $name, array $arguments)
    {
        $callerObject = $this->getObjectCaller();

        if($this->originator != $callerObject)
            throw new Exception("{$name} method called with {$callerObject} class object instead originator class that is {$this->originator}");


        return call_user_func_array([$this, $name], $arguments);
    }

    /**
     * This function identifies memento originator
     *
     * For restrict access to memento data , we should
     * identify who is originator. Then gives access
     * to data just for originator class.
     *
     * @return string
     * @throws Exception
     */
    private function autoIdentifyOriginator():string
    {
         if(!$originator = $this->getObjectCaller())
             throw new Exception('Can not identify originator for make new Memento !');

         return $originator;
    }

    /**
     * Returns object caller class name
     *
     * @return string|null
     */
    private function getObjectCaller():string|null
    {
        $trace = debug_backtrace();

        foreach ($trace  as $caller){
            if(!isset($caller['class']))
                return null;

            if($caller['class'] == self::class)
                continue;

            return $caller['class'];
        }

        return null;
    }

    /**
     * Pass data to memento
     *
     *
     * @param string|array $keyOrData if an array is passed, it will replace all old data with this new data array
     * @param mixed $value
     * @return void
     * @throws Exception
     */
    private function set(string|array $keyOrData, mixed $value= null):void
    {
        if(is_array($keyOrData))
            $this->data = $keyOrData;

        else
            if(!$value)
                throw new Exception("$value null passed for set data value !");
            else
                $this->data[$keyOrData] = $value;
    }

    /**
     * Retrieve data from memento with restrict originator
     *
     *  With restrict originator
     *
     * @param string|null $key Returns all data in array if $key is not assigned
     * @return mixed
     */
    private function get(?string $key = null):mixed
    {
        if(!isset($key))
            return $this->data;

        return $this->data[$key] ?? null;
    }

    /**
     * Export data
     *
     * Only originator can take data export
     *
     * @param bool $encrypt
     * @return string
     */
    private function export(bool $encrypt = true):string
    {
        $export = self::ORIGINATOR_DEFINITION_PREFIX_IN_EXPORT . $this->originator . serialize($this->data);

        if($encrypt)
            $export = openssl_encrypt($export, self::ENCRYPT_ALGORITHM,  self::ENCRYPT_KEY, 0, self::ENCRYPT_IV);

        return $export;
    }

    /**
     * Import data
     *
     * Restricts that is originator of the export and method caller same.
     * if no throws an error.
     *
     * @param string $export
     * @return void
     * @throws Exception
     */
    private function import(string $export):void
    {
        $decrypt = openssl_decrypt($export, self::ENCRYPT_ALGORITHM,  self::ENCRYPT_KEY, 0, self::ENCRYPT_IV);

        if(!str_starts_with($decrypt, self::ORIGINATOR_DEFINITION_PREFIX_IN_EXPORT))
            throw new Exception('Export string has not originator definition !');

        if(!str_starts_with($decrypt, self::ORIGINATOR_DEFINITION_PREFIX_IN_EXPORT . $this->originator))
            throw new Exception('Unauthorized : Export string originator and object caller were not same !');

        $decrypt = str_replace(self::ORIGINATOR_DEFINITION_PREFIX_IN_EXPORT . $this->originator, '', $decrypt);

        $this->data = unserialize($decrypt);
    }
}
