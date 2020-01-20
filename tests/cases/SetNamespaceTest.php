<?php
/**
 * Tests set namespace command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.0
 */
class SetNamespaceTest extends AyucoTestCase
{
    /**
     * Test resulting message.
     */
    public function testResultMessage()
    {
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' set namespace:PHPUnit');
        // Assert
        $this->assertEquals($execution, 'Namespace updated!');
        // Teardown
        exec('php '.WPMVC_AYUCO.' set namespace:MyApp');
    }
    /**
     * Test Main app file updated.
     */
    public function testMainNamespaceValue()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Main.php';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' set namespace:MainValue');
        // Assert
        $this->assertPregMatchContents('/namespace\sMainValue;/', $filename);
        // Teardown
        exec('php '.WPMVC_AYUCO.' set namespace:MyApp');
    }
}