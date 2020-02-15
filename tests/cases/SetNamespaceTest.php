<?php
/**
 * Tests set namespace command.
 *
 * @author Garrett Hyder <https://github.com/garretthyder>
 * @copyright 10 Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.10
 */
class SetNamespaceTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = [
        TESTING_PATH.'/app/Controllers/',
        TESTING_PATH.'/app/Models/',
        TESTING_PATH.'/app/Utility/',
    ];
    /**
     * Retore to default namespace.
     * @since 1.1.8
     */
    public function tearDown(): void
    {
        parent::tearDown();
        exec('php '.WPMVC_AYUCO.' set namespace:MyApp');
    }
    /**
     * Test resulting message.
     * @group namespace
     */
    public function testResultMessage()
    {
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' set namespace:PHPUnit');
        // Assert
        $this->assertEquals($execution, 'Namespace updated!');
    }
    /**
     * Test Main app file updated.
     * @group namespace
     */
    public function testMainNamespaceValue()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/app/Main.php';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' set namespace:MainValue');
        // Assert
        $this->assertPregMatchContents('/namespace\sMainValue/', $filename);
    }
    /**
     * Test composer file updated.
     * @group namespace
     */
    public function testComposerJson()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/composer.json';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' set namespace:ComposerValue');
        // Assert
        $this->assertPregMatchContents('/\"ComposerValue\\\\\\\"(|\s)\:(|\s)\"app/', $filename);
    }
    /**
     * Test missing namespace.
     * @group namespace
     */
    public function testMissingNamespace()
    {
        // Prepare & run
        $execution = exec('php '.WPMVC_AYUCO.' set namespace');
        // Run
        $this->assertEquals('Command "set": Expecting a namespace.', $execution);
    }
    /**
     * Test composer file updated.
     * @group namespace
     */
    public function testGlobalAppChange()
    {
        // Prepare
        $controller = TESTING_PATH.'/app/Controllers/TestController.php';
        $model = TESTING_PATH.'/app/Models/Model.php';
        $utility = TESTING_PATH.'/app/Utility/Tool.php';
        mkdir(TESTING_PATH.'/app/Controllers/', 0777, true);
        mkdir(TESTING_PATH.'/app/Models/', 0777, true);
        mkdir(TESTING_PATH.'/app/Utility/', 0777, true);
        file_put_contents($controller, '<?php namespace MyApp\Controllers; use MyApp\Models\Model; class TestController { function artu() {return;} }');
        file_put_contents($model, '<?php namespace MyApp\Models; use MyApp\Utility\Tool; class Model { function artu() {return;} }');
        file_put_contents($utility, '<?php namespace MyApp\Utility; use MyApp\Controllers\TestController; class Tool { function artu() {return;} }');
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' set namespace:GlobalApp');
        // Assert
        $this->assertStringMatchContents('namespace GlobalApp\Controllers', $controller);
        $this->assertStringMatchContents('use GlobalApp\Models\Model', $controller);
        $this->assertStringMatchContents('namespace GlobalApp\Models', $model);
        $this->assertStringMatchContents('use GlobalApp\Utility\Tool', $model);
        $this->assertStringMatchContents('namespace GlobalApp\Utility', $utility);
        $this->assertStringMatchContents('use GlobalApp\Controllers\TestController', $utility);
    }
}