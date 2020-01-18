<?php
/**
 * Tests register type  command.
 *
 * @author Ale Mostajo <http://about.me/amostajo>
 * @copyright 10 Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.6
 */
class RegisterModelControllerTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = [
        FRAMEWORK_PATH.'/environment/app/Controllers/',
        FRAMEWORK_PATH.'/environment/app/Models/',
        FRAMEWORK_PATH.'/environment/assets/views/admin/metaboxes/book/meta/',
        FRAMEWORK_PATH.'/environment/assets/views/admin/metaboxes/book/',
        FRAMEWORK_PATH.'/environment/assets/views/admin/metaboxes/',
        FRAMEWORK_PATH.'/environment/assets/views/admin/',
    ];
    /**
     * Test.
     */
    public function testRegisterAutoModelName()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Models/Book.php';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' register type:book');
        // Assert
        $this->assertEquals($execution, 'Model created!');
        $this->assertFileExists($filename);
        $this->assertFileVariableExists('type', $filename, 'book');
        $this->assertFileVariableExists('aliases', $filename);
    }
    /**
     * Test.
     */
    public function testRegisterCustomModel()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Models/MyBook.php';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' register type:book MyBook');
        // Assert
        $this->assertEquals($execution, 'Model created!');
        $this->assertFileExists($filename);
        $this->assertFileVariableExists('type', $filename, 'book');
    }
    /**
     * Test.
     */
    public function testRegisterWithController()
    {
        // Prepare
        $modelfile = FRAMEWORK_PATH.'/environment/app/Models/Book.php';
        $controllerfile = FRAMEWORK_PATH.'/environment/app/Controllers/BookController.php';
        $viewfile = FRAMEWORK_PATH.'/environment/assets/views/admin/metaboxes/book/meta/metabox.php';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' register type:book Book BookController');
        // Assert
        $this->assertEquals($execution, 'View created!');
        $this->assertFileExists($modelfile);
        $this->assertFileExists($controllerfile);
        $this->assertFileVariableExists('type', $modelfile, 'book');
        $this->assertFileVariableExists('registry_controller', $modelfile, 'BookController');
        $this->assertFileVariableExists('registry_metabox', $modelfile);
        $this->assertFileVariableExists('model', $controllerfile);
    }
}