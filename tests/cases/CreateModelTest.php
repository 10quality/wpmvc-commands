<?php
/**
 * Tests create command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.0
 */
class CreateModelTest extends AyucoTestCase
{
    /**
     * Test option model.
     */
    public function testOptionModel()
    {
        $execution = exec('php '.WPMVC_AYUCO.' create optionmodel:App app');

        $this->assertEquals($execution, 'Model created!');
        $this->assertTrue(is_file(FRAMEWORK_PATH.'/environment/app/Models/App.php'));

        unlink(FRAMEWORK_PATH.'/environment/app/Models/App.php');
    }
    /**
     * Test user model.
     */
    public function testUserModel()
    {
        $execution = exec('php '.WPMVC_AYUCO.' create usermodel:User');

        $this->assertEquals($execution, 'Model created!');
        $this->assertTrue(is_file(FRAMEWORK_PATH.'/environment/app/Models/User.php'));

        unlink(FRAMEWORK_PATH.'/environment/app/Models/User.php');
    }
    /**
     * Test category model.
     */
    public function testCategoryModel()
    {
        $execution = exec('php '.WPMVC_AYUCO.' create categorymodel:AppCategoryModel');

        $this->assertEquals($execution, 'Model created!');
        $this->assertTrue(is_file(FRAMEWORK_PATH.'/environment/app/Models/AppCategoryModel.php'));

        unlink(FRAMEWORK_PATH.'/environment/app/Models/AppCategoryModel.php');
    }
}