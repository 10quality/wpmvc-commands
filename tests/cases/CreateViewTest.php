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
class CreateViewTest extends AyucoTestCase
{
    /**
     * Test.
     */
    public function test()
    {
        $execution = exec('php '.WPMVC_AYUCO.' create view:test.test');

        $this->assertEquals($execution, 'View created!');
        $this->assertTrue(is_file(FRAMEWORK_PATH.'/environment/assets/views/test/test.php'));

        unlink(FRAMEWORK_PATH.'/environment/assets/views/test/test.php');
        rmdir(FRAMEWORK_PATH.'/environment/assets/views/test');
    }
}