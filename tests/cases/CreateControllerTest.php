<?php
/**
 * Tests create controller command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.0
 */
class CreateControllerTest extends AyucoTestCase
{
    /**
     * Test.
     */
    public function testCreateCommand()
    {
        $execution = exec('php '.WPMVC_AYUCO.' create controller:CreateController@yolo');

        $this->assertEquals($execution, 'Controller created!');
        $this->assertTrue(file_exists(FRAMEWORK_PATH.'/environment/app/Controllers/CreateController.php'));
        unlink(FRAMEWORK_PATH.'/environment/app/Controllers/CreateController.php');
        rmdir(FRAMEWORK_PATH.'/environment/app/Controllers');
    }
    /**
     * Test.
     */
    public function testAddHookCommand()
    {
        $execution = exec('php '.WPMVC_AYUCO.' add action:init ActionController@init');

        $this->assertEquals($execution, 'Controller created!');
        $this->assertTrue(file_exists(FRAMEWORK_PATH.'/environment/app/Controllers/ActionController.php'));
        unlink(FRAMEWORK_PATH.'/environment/app/Controllers/ActionController.php');
        rmdir(FRAMEWORK_PATH.'/environment/app/Controllers');
    }
}