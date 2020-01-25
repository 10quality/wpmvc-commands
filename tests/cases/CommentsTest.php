<?php
/**
 * Tests comments creation.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.9
 */
class CommentsTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = [
        TESTING_PATH.'/app/Controllers/',
        TESTING_PATH.'/app/Models/',
        TESTING_PATH.'/assets/js/',
        TESTING_PATH.'/assets/',
    ];
    /**
     * Tests the comments created during hook addition (controller).
     */
    public function testAddHookCommentsController()
    {
        // Prepare
        $mainfile = TESTING_PATH.'/app/Main.php';
        $controllerfile = TESTING_PATH.'/app/Controllers/AppController.php';
        // Execute
        exec('php '.WPMVC_AYUCO.' add action:init --comment="Test #1 comment phpunit"');
        // Assert
        $this->assertPregMatchContents('/\/\/\sTest\s\#1\scomment\sphpunit/', $mainfile);
        $this->assertPregMatchContents('/\*\sTest\s\#1\scomment\sphpunit/', $controllerfile);
    }
    /**
     * Tests the comments created during hook addition (controller). With the audit flag.
     */
    public function testAddHookCommentsControllerAudit()
    {
        // Prepare
        $mainfile = TESTING_PATH.'/app/Main.php';
        $controllerfile = TESTING_PATH.'/app/Controllers/AppController.php';
        // Execute
        exec('php '.WPMVC_AYUCO.' add action:init2 --comment="Test #2 comment phpunit" --audit');
        // Assert
        $this->assertPregMatchContents('/\/\/\sTest\s\#2\scomment\sphpunit/', $mainfile);
        $this->assertPregMatchContents('/\/\/\sAyuco\:\saddition/', $mainfile);
        $this->assertPregMatchContents('/\*\sTest\s\#2\scomment\sphpunit/', $controllerfile);
        $this->assertPregMatchContents('/\*\sAyuco\:\saddition/', $controllerfile);
    }
    /**
     * Tests the comments created during hook addition (controller). With void return.
     */
    public function testAddHookCommentsControllerVoid()
    {
        // Prepare
        $controllerfile = TESTING_PATH.'/app/Controllers/VoidController.php';
        // Execute
        exec('php '.WPMVC_AYUCO.' add action:init3 VoidController --void');
        // Assert
        $this->assertNotPregMatchContents('/\*\s\@return/', $controllerfile);
    }
    /**
     * Tests the comments created during register model command.
     */
    public function testRegisterAssetComments()
    {
        // Prepare
        $dir = TESTING_PATH . '/assets/js/';
        $jsfile = TESTING_PATH . '/assets/js/comment.js';
        $mainfile = TESTING_PATH.'/app/Main.php';
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        file_put_contents($jsfile, '');
        // Execute
        exec('php '.WPMVC_AYUCO.' register asset:js/comment.js --comment="Test #5 comment phpunit"');
        // Assert
        $this->assertPregMatchContents('/\/\/\sTest\s\#5\scomment\sphpunit/', $mainfile);
        // Test teardown
        unlink($jsfile);
    }
}
