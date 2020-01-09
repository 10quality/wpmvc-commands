<?php
/**
 * Tests generate pot command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.0
 */
class GeneratePotTest extends AyucoTestCase
{
    /**
     * Test resulting message.
     */
    public function testResultMessage()
    {
        $execution = exec('php '.WPMVC_AYUCO.' generate pot');

        $this->assertEquals('POT file generated!', $execution);
        $this->assertTrue(file_exists(FRAMEWORK_PATH.'/environment/assets/lang/my-app.pot'));
        unlink(FRAMEWORK_PATH.'/environment/assets/lang/my-app.pot');
        rmdir(FRAMEWORK_PATH.'/environment/assets/lang');
    }
    /**
     * Test resulting message.
     */
    public function testMultidomainGeneration()
    {
        $execution = exec('php '.WPMVC_AYUCO.' generate pot other-domain');

        $this->assertEquals('POT file generated!', $execution);
        $this->assertTrue(file_exists(FRAMEWORK_PATH.'/environment/assets/lang/other-domain.pot'));
        unlink(FRAMEWORK_PATH.'/environment/assets/lang/other-domain.pot');
        rmdir(FRAMEWORK_PATH.'/environment/assets/lang');
    }
}