<?php
/**
 * Tests Register command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.6
 */
class RegisterWidgetTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = FRAMEWORK_PATH.'/environment/app/Widgets/';
    /**
     * Tests missing setname command error.
     */
    public function test()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Widgets/Test.php';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' register widget:Test');
        // Assert
        $this->assertEquals('Widget registered!', $execution);
        $this->assertFileExists($filename);
    }
    /**
     * Test.
     */
    public function testPreventDuplicate()
    {
        // Execure
        exec('php '.WPMVC_AYUCO.' register widget:Original');
        $execution = exec('php '.WPMVC_AYUCO.' register widget:Original');
        // Assert
        $this->assertEquals('Widget exists!', $execution);
    }
}