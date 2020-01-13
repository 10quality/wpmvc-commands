<?php
/**
 * Tests create command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.4
 */
class CreateViewTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = FRAMEWORK_PATH.'/environment/assets/views/test';
    /**
     * Test.
     */
    public function test()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/assets/views/test/test.php';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' create view:test.test');
        // Assert
        $this->assertEquals($execution, 'View created!');
        $this->assertFileExists($filename);
    }
}