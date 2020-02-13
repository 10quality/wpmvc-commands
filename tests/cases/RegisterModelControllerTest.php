<?php
/**
 * Tests register type command.
 *
 * @author Ale Mostajo <http://about.me/amostajo>
 * @copyright 10 Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.10
 */
class RegisterModelControllerTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = [
        FRAMEWORK_PATH.'/environment/app/Controllers/',
        FRAMEWORK_PATH.'/environment/app/Models/',
        FRAMEWORK_PATH.'/environment/assets/views/admin/metaboxes/comment/meta/',
        FRAMEWORK_PATH.'/environment/assets/views/admin/metaboxes/car/meta/',
        FRAMEWORK_PATH.'/environment/assets/views/admin/metaboxes/comment/',
        FRAMEWORK_PATH.'/environment/assets/views/admin/metaboxes/car/',
        FRAMEWORK_PATH.'/environment/assets/views/admin/metaboxes/',
        FRAMEWORK_PATH.'/environment/assets/views/admin/',
        FRAMEWORK_PATH.'/environment/assets/views/',
        FRAMEWORK_PATH.'/environment/assets/',
    ];
    /**
     * Tests Posttype Model Registration with Autonaming.
     * @group models
     */
    public function testRegisterAutoModelName()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Models/Car.php';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' register type:car');
        // Assert
        $this->assertEquals('Model created!', $execution);
        $this->assertFileExists($filename);
        $this->assertFileVariableExists('type', $filename, 'car');
        $this->assertFileVariableExists('aliases', $filename);
    }
    /**
     * Tests Posttype Model Registration with custom name.
     * @group models
     */
    public function testRegisterCustomModel()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Models/MyBook.php';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' register type:book MyBook');
        // Assert
        $this->assertEquals('Model created!', $execution);
        $this->assertFileExists($filename);
        $this->assertFileVariableExists('type', $filename, 'book');
    }
    /**
     * Tests Posttype Model Registration with Controller.
     * @group models
     * @group controllers
     */
    public function testRegisterWithController()
    {
        // Prepare
        $modelfile = FRAMEWORK_PATH.'/environment/app/Models/MyCar.php';
        $controllerfile = FRAMEWORK_PATH.'/environment/app/Controllers/CarController.php';
        $viewfile = FRAMEWORK_PATH.'/environment/assets/views/admin/metaboxes/car/meta/metabox.php';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' register type:car MyCar CarController');
        // Assert
        $this->assertEquals('View created!', $execution);
        $this->assertFileExists($modelfile);
        $this->assertFileExists($controllerfile);
        $this->assertFileVariableExists('type', $modelfile, 'car');
        $this->assertFileVariableExists('registry_controller', $modelfile, 'CarController');
        $this->assertFileVariableExists('registry_metabox', $modelfile);
        $this->assertFileVariableExists('model', $controllerfile);
    }
    /**
     * Tests Model registration.
     * @group models
     */
    public function testRegisterModel()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Main.php';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' register model:MacGyver');
        // Assert
        $this->assertEquals('Model registered!', $execution);
        $this->assertPregMatchContents('/add_model\((|\s)\'MacGyver\'/', $filename);
    }
    /**
     * Tests the comments created during register type command.
     * @group models
     * @group comments
     */
    public function testRegisterComments()
    {
        // Prepare
        $mainfile = TESTING_PATH.'/app/Main.php';
        $controllerfile = TESTING_PATH.'/app/Controllers/CommentController.php';
        // Execute
        exec('php '.WPMVC_AYUCO.' register type:comment Comment CommentController --comment="Test #3 comment phpunit"');
        // Assert
        $this->assertPregMatchContents('/\/\/\sTest\s\#3\scomment\sphpunit/', $mainfile);
    }
    /**
     * Tests the comments created during register model command.
     * @group models
     * @group comments
     */
    public function testRegisterModelComments()
    {
        // Prepare
        $mainfile = TESTING_PATH.'/app/Main.php';
        // Execute
        exec('php '.WPMVC_AYUCO.' register model:Comment2 --comment="Test #4 comment phpunit"');
        // Assert
        $this->assertPregMatchContents('/\/\/\sTest\s\#4\scomment\sphpunit/', $mainfile);
    }
}
