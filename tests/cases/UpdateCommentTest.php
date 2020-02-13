<?php
/**
 * Tests update comment feature.
 *
 * @author Ale Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.10
 */
class UpdateCommentTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = FRAMEWORK_PATH.'/environment/app/Controllers/';
    /**
     * Tests.
     * @group comments
     */
    public function testInController()
    {
        // Prepare
        $mainfile = FRAMEWORK_PATH.'/environment/app/Main.php';
        $controllerfile = FRAMEWORK_PATH.'/environment/app/Controllers/CommentController.php';
        // Execute
        exec('php '.WPMVC_AYUCO.' set version:1.0.0');
        exec('php '.WPMVC_AYUCO.' add filter:body_class CommentController');
        exec('php '.WPMVC_AYUCO.' set version:2.1.9');
        exec('php '.WPMVC_AYUCO.' add action:init CommentController');
        // Assert
        $this->assertStringMatchContents('@version 2.1.9', $mainfile);
        $this->assertStringMatchContents('@version 2.1.9', $controllerfile);
        $this->assertFileFunctionExists('body_class', $controllerfile);
        $this->assertFileFunctionExists('init', $controllerfile);
        // Tear down
        exec('php '.WPMVC_AYUCO.' set version:1.0.0');
    }
}