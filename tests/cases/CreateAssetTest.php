<?php
/**
 * Tests create command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.5
 */
class CreateAssetTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = [
        FRAMEWORK_PATH.'/environment/assets/raw/js',
        FRAMEWORK_PATH.'/environment/assets/raw/css',
        FRAMEWORK_PATH.'/environment/assets/raw',
    ];
    /**
     * Test.
     */
    public function testJs()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/assets/raw/js/yolo.js';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' create js:yolo');
        // Assert
        $this->assertEquals($execution, 'js asset created!');
        $this->assertFileExists($filename);
    }
    /**
     * Test.
     */
    public function testJsJquery()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/assets/raw/js/jquery.yolo.js';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' create js:jquery.yolo jquery');
        // Assert
        $this->assertEquals($execution, 'js asset created!');
        $this->assertFileExists($filename);
        $this->assertPregMatchContents('/jQuery/', $filename);
    }
    /**
     * Test.
     */
    public function testCss()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/assets/raw/css/yolo.css';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' create css:yolo');
        // Assert
        $this->assertEquals($execution, 'css asset created!');
        $this->assertFileExists($filename);
    }
}