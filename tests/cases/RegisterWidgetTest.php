<?php
/**
 * Tests Register command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.10
 */
class RegisterWidgetTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = FRAMEWORK_PATH.'/environment/app/Widgets/';
    /**
     * Tests Widget registration.
     * @group widgets
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
     * Tests Widget Duplication is avoided.
     * @group widgets
     * @group duplication
     */
    public function testPreventDuplicate()
    {
        // Execure
        exec('php '.WPMVC_AYUCO.' register widget:Original');
        $execution = exec('php '.WPMVC_AYUCO.' register widget:Original');
        // Assert
        $this->assertEquals('Widget exists!', $execution);
    }
    /**
     * Tests Widget registration.
     * @group widgets
     * @group comments
     */
    public function testComments()
    {
        // Prepare
        $mainfile = TESTING_PATH.'/app/Main.php';
        $filename = FRAMEWORK_PATH.'/environment/app/Widgets/Comment.php';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' register widget:Comment --comment="Widget comment phpunit"');
        // Assert
        $this->assertPregMatchContents('/\/\/\sWidget\scomment\sphpunit/', $mainfile);
        $this->assertPregMatchContents('/\*\sWidget\scomment\sphpunit/', $filename);
    }
}
