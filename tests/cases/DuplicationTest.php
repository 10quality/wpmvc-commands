<?php
/**
 * Tests duplication.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.6
 */
class DuplicationTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = TESTING_PATH.'/app/Controllers/';
    /**
     * Test.
     */
    public function testControllers()
    {
        // Prepare
        $filename = TESTING_PATH . '/app/Controllers/TestController.php';
        // Execure
        exec('php '.WPMVC_AYUCO.' create controller:TestController@yolo');
        $execution = exec('php '.WPMVC_AYUCO.' create controller:TestController@yolo');
        // Assert
        $this->assertEquals('Method exists!', $execution);
        $this->assertPregMatchCount(1, '/function(|\s)yolo(|\s)\(/', $filename);
    }
    /**
     * Test.
     */
    public function testMainAddCommand()
    {
        // Prepare
        $filename = TESTING_PATH . '/app/Main.php';
        // Execure
        exec('php '.WPMVC_AYUCO.' add action:init DuplicateController@init');
        $execution = exec('php '.WPMVC_AYUCO.' add action:init DuplicateController@init');
        // Assert
        $this->assertEquals('Hook call exists!', $execution);
        $this->assertPregMatchCount(1, '/add_action\(\'init\'\,\s\'DuplicateController@init\'/', $filename);
    }
    /**
     * Test.
     */
    public function testMainRegisterTypeCommand()
    {
        // Prepare
        $filename = TESTING_PATH . '/app/Main.php';
        // Execure
        exec('php '.WPMVC_AYUCO.' register type:book');
        $execution = exec('php '.WPMVC_AYUCO.' register type:book');
        // Assert
        $this->assertEquals('Hook call exists!', $execution);
        $this->assertPregMatchCount(1, '/add_model\(\'Book\'/', $filename);
    }
    /**
     * Test.
     */
    public function testMainRegisterModelCommand()
    {
        // Prepare
        $filename = TESTING_PATH . '/app/Main.php';
        // Execure
        exec('php '.WPMVC_AYUCO.' register model:Rocket');
        $execution = exec('php '.WPMVC_AYUCO.' register model:Rocket');
        // Assert
        $this->assertEquals('Model registration exists!', $execution);
        $this->assertPregMatchCount(1, '/add_model\(\'Rocket\'/', $filename);
    }
    /**
     * Test.
     */
    public function testMainRegisterAssetCommand()
    {
        // Prepare
        $filename = TESTING_PATH . '/app/Main.php';
        // Execure
        exec('php '.WPMVC_AYUCO.' register asset:js/test.js');
        $execution = exec('php '.WPMVC_AYUCO.' register asset:js/test.js');
        // Assert
        $this->assertEquals('Asset registration exists!', $execution);
        $this->assertPregMatchCount(1, '/add_asset\(\'js\/test.js\'/', $filename);
    }
}