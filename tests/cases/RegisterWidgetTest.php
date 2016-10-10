<?php
/**
 * Tests Register command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.0
 */
class RegisterWidgetTest extends AyucoTestCase
{
    /**
     * Tests missing setname command error.
     */
    public function test()
    {
        $execution = exec('php '.WPMVC_AYUCO.' register widget:Test');

        $this->assertEquals($execution, 'Widget registered!');
        $this->assertTrue(is_file(FRAMEWORK_PATH.'/environment/app/Widgets/Test.php'));

        unlink(FRAMEWORK_PATH.'/environment/app/Widgets/Test.php');
        rmdir(FRAMEWORK_PATH.'/environment/app/Widgets');
    }
}