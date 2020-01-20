<?php
/**
 * Tests predefined hooks definition.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.7
 */
class HooksTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = FRAMEWORK_PATH.'/environment/app/Controllers';
    /**
     * Tests adding predefined hook.
     */
    public function testPredefinedHook()
    {
        // Prepare
        $mainfile = FRAMEWORK_PATH.'/environment/app/Main.php';
        $controllerfile = FRAMEWORK_PATH.'/environment/app/Controllers/TestController.php';
        // Execure
        exec('php '.WPMVC_AYUCO.' add action:admin_bar_menu TestController@admin_bar_menu');
        // Assert
        $this->assertPregMatchContents('/\$this\-\>add_action\((|\s)\'admin_bar_menu\'\,(|\s)\'TestController@admin_bar_menu\'\s\)/', $mainfile);
        $this->assertPregMatchContents('/function\sadmin_bar_menu\((|\s)\$wp_admin_bar/', $controllerfile);
    }
    /**
     * Tests adding custom hook.
     */
    public function testHookControllerMethodName()
    {
        // Prepare
        $mainfile = FRAMEWORK_PATH.'/environment/app/Main.php';
        $controllerfile = FRAMEWORK_PATH.'/environment/app/Controllers/TestController.php';
        // Execure
        exec('php '.WPMVC_AYUCO.' add action:test_hook TestController');
        // Assert
        $this->assertPregMatchContents('/\$this\-\>add_action\((|\s)\'test_hook\'\,(|\s)\'TestController@test_hook\'\s\)/', $mainfile);
        $this->assertFileFunctionExists('test_hook', $controllerfile);
    }
}
