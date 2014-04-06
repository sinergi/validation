<?php
namespace Sinergi\Validation\Tests;

use PHPUnit_Framework_TestCase;
use Sinergi\Validation\Autoloader;

class AutoloaderTest extends PHPUnit_Framework_TestCase
{
    public function testRegister()
    {
        Autoloader::register();
        $this->assertContains(['Sinergi\\Validation\\Autoloader', 'autoload'], spl_autoload_functions());
    }

    public function testAutoload()
    {
        $declared = get_declared_classes();
        $declaredCount = count($declared);
        Autoloader::autoload('Foo');
        $this->assertEquals(
            $declaredCount,
            count(get_declared_classes()),
            'Sinergi\\Validation\\Autoloader::autoload() is trying to load classes outside of the Sinergi\\Validation namespace'
        );
        Autoloader::autoload('Sinergi\\Validation\\Validation');
        $this->assertTrue(
            in_array('Sinergi\\Validation\\Validation', get_declared_classes()),
            'Sinergi\\Validation\\Autoloader::autoload() failed to autoload the Sinergi\\Validation\\Validation class'
        );
    }
}