<?php
/**
 * Tests create controller command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.4
 */
class CreateControllerTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = FRAMEWORK_PATH.'/environment/app/Controllers/';
    /**
     * Test.
     */
    public function testCreateCommand()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/CreateController.php';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' create controller:CreateController@yolo');
        // Assert
        $this->assertEquals($execution, 'Controller created!');
        $this->assertFileExists($filename);
        $this->assertFileFunctionExists('yolo', $filename);
    }
    /**
     * Test.
     */
    public function testAddHookCommand()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/ActionController.php';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' add action:init ActionController@init');
        // Assert
        $this->assertEquals($execution, 'Controller created!');
        $this->assertFileExists($filename);
        $this->assertFileFunctionExists('init', $filename);
    }
    /**
     * Test.
     */
    public function testCreateCommandMultipleMethods()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/CreateController.php';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' create controller:CreateController@yolo@init@twice');
        // Assert
        $this->assertEquals($execution, 'Controller created!');
        $this->assertFileExists($filename);
        $this->assertFileFunctionExists('yolo', $filename);
        $this->assertFileFunctionExists('init', $filename);
        $this->assertFileFunctionExists('twice', $filename);
    }
}