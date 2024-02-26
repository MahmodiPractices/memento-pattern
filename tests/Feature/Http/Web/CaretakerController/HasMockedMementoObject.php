<?php

namespace Tests\Feature\Http\Web\CaretakerController;

use App\Factory\MementoObject;
use App\Models\Machine;
use Illuminate\Support\Str;
use PHPUnit\Framework\MockObject\Exception;

trait HasMockedMementoObject
{
    /**
     * @param Machine $machine
     * @return MementoObject
     * @throws Exception
     */
    protected function mementoObjectMockBuilder(Machine $machine):MementoObject
    {
        $mock = $this->createMock(MementoObject::class);

        $mock->method('__call')
            ->willReturnCallback(function($name, $args) use ($machine){
                if($name == 'set')
                    return;

                if($name == 'get')
                    if(isset($machine->{$args[0]}))
                        return $machine->{$args[0]};

                if($name == 'export')
                    return Str::random();

                return;
            });

        return $mock;
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function injectMementoObjectMockToContainer(?Machine $machine  = null)
    {
        $machine = $machine ?? Machine::factory()->make();

        $this->app->bind(MementoObject::class, function($app, $parameters) use ($machine){
            return $this->mementoObjectMockBuilder($machine);
        });
    }
}
