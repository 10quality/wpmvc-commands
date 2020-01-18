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
     * Test resulting message.
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
}