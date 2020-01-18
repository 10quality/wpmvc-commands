<?php
/**
 * Tests WPPrinter
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.7
 */
class PrinterTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = FRAMEWORK_PATH.'/environment/app/Controllers';
    /**
     * Test.
     */
    public function testPrintedMethods()
    {
        // Prepare
        $mainfile = FRAMEWORK_PATH.'/environment/app/Main.php';
        $controllerfile = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        exec('php '.WPMVC_AYUCO.' add action:added_existing_user PrintController@added_user');
        // Assert
        // - No spaces added to init() and on_admin()
        $this->assertPregMatchContents('/init\(\)/', $mainfile);
        $this->assertPregMatchContents('/on_admin\(\)/', $mainfile);
        // - Spaces added in between
        $this->assertStringMatchContents('$this->add_action( \'added_existing_user\', \'PrintController@added_user\' )', $mainfile);
        $this->assertStringMatchContents('function added_user( $user_id, $result )', $controllerfile);
    }
    /**
     * Test.
     */
    public function testPrintedIfWhileFor()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { function artu ($artu) { if($artu==0) return; while($artu) $i++; for($i;$i>0;--$i) {$i++;} } }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertStringMatchContents('function artu( $artu )', $filename);
        $this->assertStringMatchContents('if ( $artu == 0 )', $filename);
        $this->assertStringMatchContents('while ( $artu )', $filename);
        $this->assertStringMatchContents('for ( $i; $i > 0; --$i )', $filename);
    }
    /**
     * Test.
     */
    public function testPrintedArrays()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { function artu ($artu) { $array=array(1,2,3); $array2=[5,6,\'7\']; } }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertStringMatchContents('$array = array( 1, 2, 3 );', $filename);
        $this->assertStringMatchContents('$array2 = [ 5, 6, \'7\' ];', $filename);
    }
}