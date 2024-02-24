<?php

namespace Tests\Unit\App\Factory;

use App\Factory\MementoObject;
use PHPUnit\Framework\TestCase;

class MementoTest extends TestCase
{
    /**
     * Asserts the MementoObject restrict and block set data
     * request for another classes
     *
     * @return void
     */
    public function test_memento_throws_exception_when_set_data_request_comes_from_another_class_except_originator()
    {
        $memento = new MementoObject();

        $proxy = new MementoProxy($memento);

        $this->expectException(\Exception::class);

        $proxy->set('my-name', 'Mr tester');
    }

    /**
     * Asserts the MementoObject restrict and block get data
     * request for another classes
     *
     * @return void
     */
    public function test_memento_throws_exception_when_get_data_request_comes_from_another_class_except_originator()
    {
        $memento = new MementoObject('Some\One');

        $this->expectException(\Exception::class);

        $memento->get('my-name');
    }

    /**
     * Asserts the MementoObject restrict and block export data
     * request for another classes
     *
     * @return void
     */
    public function test_memento_throws_exception_when_export_data_request_comes_from_another_class_except_originator()
    {
        $memento = new MementoObject();

        $memento->set('my-name', 'Mr tester');

        $this->expectException(\Exception::class);

        $proxy = new MementoProxy($memento);

        $proxy->export();
    }

    /**
     * Asserts the MementoObject throws an exception when
     * object that sends import request is not originator of
     * exported data
     *
     * @return void
     */
    public function test_memento_throws_exception_when_import_data_request_comes_from_another_class_except_originator()
    {
        $memento = new MementoObject();

        $memento->set('my-name', 'Mr tester');

        $export = $memento->export();

        $this->expectException(\Exception::class);

        $proxy = new MementoProxy(
            MementoProxy::buildMemento()
        );

        $proxy->import($export);
    }

    /**
     * Asserts memento returns correct data after export and import operations
     *
     * @return void
     * @throws \Exception
     */
    public function test_memento_returns_correct_data_after_import_and_export()
    {
        $data = [
            'my-name' => 'Mr tester'
        ];

        $memento = new MementoObject();

        $memento->set($data);

        $export = $memento->export();

        $memento = new MementoObject();

        $memento->import($export);

        $this->assertSame($data, $memento->get());
    }

    /**
     * Asserts memento obscures after encrypt export and the actual
     * amount of data doesn't seem in export string
     *
     * @return void
     */
    public function test_memento_obscures_data_in_encrypt_export_type()
    {
        $data = [
            'my-name' => 'Mr tester',
            'my-target' => 'obfuscation',
            'level' => 'important'
        ];

        $memento = new MementoObject();

        $memento->set($data);

        $export = $memento->export(true);

        foreach ($data as $key => $value){
            $this->assertStringNotContainsString($key, $export);

            $this->assertStringNotContainsString($value, $export);
        }
    }
}


/**
 * Proxy between test class and MementoObject for testing restrict originator access
 */
class MementoProxy
{
    /**
     * @param string|null $originator For define custom originator class name
     * @return MementoObject
     */
    public static function buildMemento(?string $originator = null):MementoObject
    {
        return new MementoObject($originator);
    }

    /**
     * @param MementoObject $memento
     */
    public function __construct(
        public MementoObject $memento
    ){}

    /**
     * Pass data to memento
     *
     *
     * @param string|array $keyOrData if an array is passed, it will replace all old data with this new data array
     * @param mixed $value
     * @return void
     */
    public function set(string|array $keyOrData, mixed $value= null):void
    {
            $this->memento->set($keyOrData, $value);
    }

    /**
     * Retrieve data from memento
     *
     * @param string|null $key Returns all data in array if $key is not assigned
     * @return mixed
     */
    public function get(?string $key = null):mixed
    {
        return $this->memento->get($key);
    }

    /**
     * Export data
     *
     * @param bool $encrypt
     * @return string
     */
    public function export(bool $encrypt = true):string
    {
        return $this->memento->export($encrypt);
    }

    /**
     * Import data
     *
     * @param string $export
     * @return void
     */
    public function import(string $export):void
    {
        $this->memento->import($export);
    }
}

