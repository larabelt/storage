<?php

use Belt\Core\Testing\BeltTestCase;
use Belt\Clip\Adapters\BaseAdapter;
use Belt\Clip\Adapters\AdapterFactory;
use Belt\Clip\Adapters\LocalAdapter;

class AdapterFactoryTest extends BeltTestCase
{

    /**
     * @covers \Belt\Clip\Adapters\AdapterFactory::up
     */
    public function test()
    {
        app()['config']->set('belt.clip.drivers.AdapterFactoryTest', [
            'disk' => 'public',
            'adapter' => LocalAdapter::class,
            'prefix' => 'testing',
            'src' => [
                'root' => 'http://localhost',
            ],
            'secure' => [
                'root' => 'http://localhost',
            ],
        ]);

        $this->assertEmpty(array_get(AdapterFactory::$adapters, 'AdapterFactoryTest'));
        $this->assertInstanceOf(BaseAdapter::class, AdapterFactory::up('AdapterFactoryTest'));
        $this->assertNotEmpty(AdapterFactory::$adapters);
        $this->assertInstanceOf(BaseAdapter::class, AdapterFactory::up('AdapterFactoryTest'));

        try {
            $exception = false;
            AdapterFactory::up('invalid');
        } catch (\Exception $e) {
            $exception = true;
        }
        $this->assertTrue($exception);
    }

}