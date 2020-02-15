<?php
/**
 * Tests WPPrinter
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.10
 */
class PrinterTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = FRAMEWORK_PATH.'/environment/app/Controllers';
    /**
     * Tests action methods printed.
     * @group printer
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
     * Tests printed if while and for methods.
     * @group printer
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
     * Tests printed arrays.
     * @group printer
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
    /**
     * Tests printed empty arrays.
     * @group printer
     */
    public function testPrintedEmptyArrays()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { function artu ($artu) { $array=array( ); $array2=[ ]; } }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertStringMatchContents('$array = array();', $filename);
        $this->assertStringMatchContents('$array2 = [];', $filename);
    }
    /**
     * Tests printed not lengthy arrays.
     * @group printer
     */
    public function testPrintedNotLengthyArrays()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { public $artu=['
            .'\'id\'=>5,\'name\'=>\'Artu\','
            .']; }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertStringMatchContents('$artu = [ \'id\' => 5, \'name\' => \'Artu\' ]', $filename);
    }
    /**
     * Tests printed lengthy arrays.
     * @group printer
     */
    public function testPrintedLengthyArrays()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { public $artu=['
            .'\'id\'=>5,'
            .'\'name\'=>\'Artu\','
            .'\'fullname\'=>\'Amazing artu the printed test.\','
            .'\'lastnae\'=>\'Amazing artu the printed test.\','
            .'\'address\'=>\'Amazing artu the printed test.\','
            .'\'location\'=>\'Amazing artu the printed test.\','
            .']; }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertNotStringMatchContents('$artu = [ \'id\' => 5, \'name\'', $filename);
    }
    /**
     * Tests printed not lengthy arrays.
     * @group printer
     */
    public function testPrintedNotLengthySubArrays()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { public $artu=['
            .'\'id\'=>5,'
            .'\'name\'=>\'Artu\','
            .'\'fullname\'=>\'Amazing artu the printed test.\','
            .'\'lastnae\'=>\'Amazing artu the printed test.\','
            .'\'address\'=>\'Amazing artu the printed test.\','
            .'\'sub\'=>[\'id\'=>7,\'name\'=>\'James\'],'
            .']; }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertStringMatchContents('\'sub\' => [ \'id\' => 7, \'name\' => \'James\' ]', $filename);
    }
    /**
     * Tests printed lengthy arrays.
     * @group printer
     */
    public function testPrintedLengthySubArrays()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { public $artu=['
            .'\'id\'=>5,'
            .'\'name\'=>\'Artu\','
            .'\'fullname\'=>\'Amazing artu the printed test.\','
            .'\'lastnae\'=>\'Amazing artu the printed test.\','
            .'\'address\'=>\'Amazing artu the printed test.\','
            .'\'sub\'=>[\'id\'=>7,\'name\'=>\'James\','
                .'\'fullname\'=>\'Amazing artu the printed test.\','
                .'\'lastnae\'=>\'Amazing artu the printed test.\','
                .'\'address\'=>\'Amazing artu the printed test.\','
                .'],'
            .']; }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertNotStringMatchContents('\'sub\' => [ \'id\' => 7, \'name\' => \'James\' ]', $filename);
    }
    /**
     * Tests printed not lengthy if conditions.
     * @group printer
     */
    public function testPrintedNotLengthyIfCon()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { function test() { if ('
            .'$id1 === true'
            .'&& $id_name_2 === true'
            .') return; } }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertStringMatchContents('( $id1 === true && $id_name_2 === true )', $filename);
    }
    /**
     * Tests printed lengthy if conditions.
     * @group printer
     */
    public function testPrintedLengthyIfCon()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { function test() { if ('
            .'$id1 === true'
            .'&& $id_name_2 === true'
            .'&& $id_name_3 === true'
            .'&& $id_name_4 === true'
            .'&& $id_name_5 === $id_name_2'
            .') return; } }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertNotStringMatchContents('( $id1 === true && $id_name_2 === true', $filename);
    }
    /**
     * Tests printed lengthy elseif conditions.
     * @group printer
     */
    public function testPrintedLengthyElseIfCon()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { function test() { if (true) { return; } elseif ('
            .'$id1 === true'
            .'&& $id_name_2 === true'
            .'&& $id_name_3 === true'
            .'&& $id_name_4 === true'
            .'&& $id_name_5 === $id_name_2'
            .') return; } }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertStringMatchContents('} elseif (', $filename);
        $this->assertNotStringMatchContents('( $id1 === true && $id_name_2 === true', $filename);
    }
    /**
     * Tests printed lengthy while conditions.
     * @group printer
     */
    public function testPrintedLengthyWhileCon()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { function test() { while('
            .'$id1 === true'
            .'&& $id_name_2 === true'
            .'&& $id_name_3 === true'
            .'&& $id_name_4 === true'
            .'&& $id_name_5 === $id_name_2'
            .') return; } }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertStringMatchContents('while (', $filename);
        $this->assertNotStringMatchContents('( $id1 === true && $id_name_2 === true', $filename);
    }
    /**
     * Tests printed lengthy do while conditions.
     * @group printer
     */
    public function testPrintedLengthyDoWhileCon()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { function test() { do { return; } while('
            .'$id1 === true'
            .'&& $id_name_2 === true'
            .'&& $id_name_3 === true'
            .'&& $id_name_4 === true'
            .'&& $id_name_5 === $id_name_2'
            .'); } }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertStringMatchContents('while (', $filename);
        $this->assertNotStringMatchContents('( $id1 === true && $id_name_2 === true', $filename);
    }
    /**
     * Tests printed lengthy nested conditions.
     * @group printer
     */
    public function testPrintedLengthyNestedCon()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { function test() { if ('
            .'$id1 === true'
            .'&& ($id_name_2 === true'
            .'&& $id_name_3 === true'
            .'&& $id_name_4 === true'
            .'&& $id_name_5 === $id_name_2'
            .')) return; } }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertNotStringMatchContents('( $id_name_2 === true && $id_name_3 === true', $filename);
    }
    /**
     * Tests printed new statement.
     * @group printer
     */
    public function testPrintedNew()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { function test() { '
            .'$var = new Abc ( );'
            .'$var2 =  new Cba($var);'
            .' } }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertStringMatchContents('new Abc()', $filename);
        $this->assertStringMatchContents('new Cba( $var )', $filename);
    }
    /**
     * Tests printed multiline string concat.
     * @group printer
     */
    public function testPrintedMultilineString()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { function test() { '
            .'$var = \'long string\' . $variable . \'very long string here\' . $long_variable . \'long string\' . $long_long_longest_variable;'
            .'$var2 = \'long string\' . $variable;'
            .' } }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertStringMatchContents('$var2 = \'long string\' . $variable;', $filename);
        $this->assertNotStringMatchContents('$var = \'long string\' . $variable . \'very long string here\'', $filename);
    }
    /**
     * Tests printed multiline and/or variable assignment.
     * @group printer
     */
    public function testPrintedMultilineAndOr()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { function test() { '
            .'$var = 1564879 > 65268 && $variable === true && empty( $second_variable ) && 1231546879 > 156489 || ! intval( $variable ) > 0;'
            .'$var2 = $variable === true && strlen( $var );'
            .' } }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController');
        // Assert
        $this->assertStringMatchContents('$var2 = $variable === true && strlen( $var )', $filename);
        $this->assertNotStringMatchContents('$var = 1564879 > 65268 && $variable === true', $filename);
    }
    /**
     * Test with option --nopretty.
     * @group printer
     * @group nopretty
     */
    public function testNoPretty()
    {
        // Prepare
        $dir = FRAMEWORK_PATH.'/environment/app/Controllers/';
        $filename = FRAMEWORK_PATH.'/environment/app/Controllers/PrintController.php';
        // Execure
        if (!is_dir($dir)) mkdir($dir);
        file_put_contents($filename, '<?php class PrintController { function test() { '
            .'$var = 1564879 > 65268 && $variable === true && empty( $second_variable ) && 1231546879 > 156489 || ! intval( $variable ) > 0;'
            .'$var2 = $variable === true && strlen( $var );'
            .' } }');
        exec('php '.WPMVC_AYUCO.' add action:init PrintController --nopretty');
        // Assert
        $this->assertStringMatchContents('$var = 1564879 > 65268 && $variable === true', $filename);
        $this->assertStringMatchContents('public function init()', $filename);
    }
}
