<?php
/**
 * Tests the prettify command.
 *
 * @author Ale Mostajo
 * @copyright 10 Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.10
 */
class PrettifyTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = [
        TESTING_PATH.'/app/Models',
        TESTING_PATH.'/app/Classes',
        TESTING_PATH.'/app/Functions',
        TESTING_PATH.'/app/Boot',
    ];
    /**
     * Tests prettify on framework folders.
     * @group prettify
     * @group models
     */
    public function testPrettifyModels()
    {
        // Prepare
        $dir = TESTING_PATH.'/app/Models/';
        $filename = TESTING_PATH.'/app/Models/Custom.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class Custom { function artu($artu) {$array2=[5,6,\'7\'];} }');
        exec('php '.WPMVC_AYUCO.' prettify');
        // Assert
        $this->assertStringMatchContents('function artu( $artu )', $filename);
        $this->assertStringMatchContents('$array2 = [ 5, 6, \'7\' ];', $filename);
    }
    /**
     * Tests prettify on custom folders and files.
     * @group prettify
     */
    public function testPrettifyCustomClass()
    {
        // Prepare
        $dir = TESTING_PATH.'/app/Classes/';
        $filename = TESTING_PATH.'/app/Classes/Test.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class Test { function artu($artu) {$array2=[5,6,\'7\'];} }');
        exec('php '.WPMVC_AYUCO.' prettify');
        // Assert
        $this->assertStringMatchContents('function artu( $artu )', $filename);
        $this->assertStringMatchContents('$array2 = [ 5, 6, \'7\' ];', $filename);
    }
    /**
     * Tests prettify on function files.
     * @group prettify
     * @group functions
     */
    public function testPrettifyFunctionFile()
    {
        // Prepare
        $dir = TESTING_PATH.'/app/Functions/';
        $filename = TESTING_PATH.'/app/Functions/functions.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php function artu($artu) {$array2=[5,6,\'7\'];}');
        exec('php '.WPMVC_AYUCO.' prettify');
        // Assert
        $this->assertStringMatchContents('function artu( $artu )', $filename);
        $this->assertStringMatchContents('$array2 = [ 5, 6, \'7\' ];', $filename);
    }
    /**
     * Tests preserved configuretion files.
     * @group prettify
     * @group config
     */
    public function testNoPrettifyConfigFiles()
    {
        // Prepare
        $dir = TESTING_PATH.'/app/Config/';
        $filename = TESTING_PATH.'/app/Config/test.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php return [\'test\'     => 123,321];}');
        exec('php '.WPMVC_AYUCO.' prettify');
        // Assert
        $this->assertStringMatchContents('return [\'test\'     => 123,321]', $filename);
        $this->assertNotStringMatchContents('[ \'test\' => 123, 321 ]', $filename);
        // Teardown
        unlink($filename);
    }
    /**
     * Tests preserved boot file.
     * @group prettify
     */
    public function testNoPrettifyBootFile()
    {
        // Prepare
        $dir = TESTING_PATH.'/app/Boot/';
        $filename = TESTING_PATH.'/app/Boot/bootstrap.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php return [\'test\'     => 123,321];}');
        exec('php '.WPMVC_AYUCO.' prettify');
        // Assert
        $this->assertStringMatchContents('return [\'test\'     => 123,321]', $filename);
        $this->assertNotStringMatchContents('[ \'test\' => 123, 321 ]', $filename);
    }
    /**
     * Tests prettify on root file.
     * @group prettify
     */
    public function testPrettifyRootFile()
    {
        // Prepare
        $filename = TESTING_PATH.'/app/Root.php';
        // Execure
        file_put_contents($filename, '<?php class Root { function artu($artu) {$array2=[5,6,\'7\'];} }');
        exec('php '.WPMVC_AYUCO.' prettify');
        // Assert
        $this->assertStringMatchContents('function artu( $artu )', $filename);
        $this->assertStringMatchContents('$array2 = [ 5, 6, \'7\' ];', $filename);
        // Teardown
        unlink($filename);
    }
}
