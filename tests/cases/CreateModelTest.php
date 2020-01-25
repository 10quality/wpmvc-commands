<?php
/**
 * Tests create command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.6
 */
class CreateModelTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = FRAMEWORK_PATH.'/environment/app/Models/';
    /**
     * Test option model.
     */
    public function testOptionModel()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Models/App.php';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' create optionmodel:App app');
        // Assert
        $this->assertEquals('Model created!', $execution);
        $this->assertFileExists($filename);
        $this->assertFileVariableExists('id', $filename, 'app');
    }
    /**
     * Test user model.
     */
    public function testUserModel()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Models/User.php';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' create usermodel:User');
        // Assert
        $this->assertEquals('Model created!', $execution);
        $this->assertFileExists($filename);
    }
    /**
     * Test category model.
     */
    public function testCategoryModel()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Models/AppCategoryModel.php';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' create categorymodel:AppCategoryModel');
        // Assert
        $this->assertEquals('Model created!', $execution);
        $this->assertFileExists($filename);
        $this->assertPregMatchContents('/TermModel/', $filename);
    }
    /**
     * Test term model.
     */
    public function testTermModel()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Models/AppTermModel.php';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' create termmodel:AppTermModel');
        // Assert
        $this->assertEquals('Model created!', $execution);
        $this->assertFileExists($filename);
    }
    /**
     * Test term model w/ taxonomy.
     */
    public function testTermModelWithTax()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Models/AppTermModel.php';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' create termmodel:AppTermModel custom_tax');
        // Assert
        $this->assertEquals('Model created!', $execution);
        $this->assertFileExists($filename);
        $this->assertFileVariableExists('model_taxonomy', $filename, 'custom_tax');
    }
    /**
     * Tests default coments.
     */
    public function testDefaultComments()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Models/DefaultComment.php';
        // Execure
        exec('php '.WPMVC_AYUCO.' create model:DefaultComment');
        // Assert
        $this->assertPregMatchContents('/\*\sDefaultComment\smodel\./', $filename);
    }
    /**
     * Tests coments.
     */
    public function testComments()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Models/Comment.php';
        // Execure
        exec('php '.WPMVC_AYUCO.' create model:Comment --comment="Model comment phpunit"');
        // Assert
        $this->assertPregMatchContents('/\*\sModel\scomment\sphpunit/', $filename);
        $this->assertNotPregMatchContents('/\*\sComment\smodel\./', $filename);
    }
}
