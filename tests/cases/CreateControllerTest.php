<?php
/**
 * Tests create controller command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.6
 */
class CreateControllerTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = FRAMEWORK_PATH.'/environment/app/Controllers/';
    /**
     * Tests create controller command.
     */
    public function testCreateCommand()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/CreateController.php';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' create controller:CreateController@yolo');
        // Assert
        $this->assertEquals('Controller created!', $execution);
        $this->assertFileExists($filename);
        $this->assertFileFunctionExists('yolo', $filename);
    }
    /**
     * Tests add action hook creates controller.
     */
    public function testAddHookCommand()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/ActionController.php';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' add action:init ActionController@init');
        // Assert
        $this->assertEquals('Controller created!', $execution);
        $this->assertFileExists($filename);
        $this->assertFileFunctionExists('init', $filename);
    }
    /**
     * Tests create controller w/ multiple methods.
     */
    public function testCreateCommandMultipleMethods()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/CreateController.php';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' create controller:CreateController@yolo@init@twice');
        // Assert
        $this->assertEquals('Controller created!', $execution);
        $this->assertFileExists($filename);
        $this->assertFileFunctionExists('yolo', $filename);
        $this->assertFileFunctionExists('init', $filename);
        $this->assertFileFunctionExists('twice', $filename);
    }
}
