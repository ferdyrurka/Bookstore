<?php

namespace App\Tests\Model;

use App\Model\Device;
use PHPUnit\Framework\TestCase;

/**
 * Class DeviceTest
 * @package App\Tests\Model
 */
class DeviceTest extends TestCase
{
    public function testGet(): void
    {
        $device = new Device();
        $this->assertEquals('Windows 10', $device->get('user Windows NT 10 agent'));
        $this->assertEquals('other', $device->get('test failed'));
        $this->assertEquals('other', $device->get(''));
    }
}
