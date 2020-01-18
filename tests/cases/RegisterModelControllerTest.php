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
        FRAMEWORK_PATH.'/environment/assets/views/admin/metaboxes/car/meta/',
        FRAMEWORK_PATH.'/environment/assets/views/admin/metaboxes/car/',
        FRAMEWORK_PATH.'/environment/assets/views/admin/metaboxes/',
        FRAMEWORK_PATH.'/environment/assets/views/admin/',
    ];
    /**
     * Test.
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
     * Test.
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
     * Test.
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
     * Test.
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
}