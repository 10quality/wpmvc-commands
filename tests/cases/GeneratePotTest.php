<?php
/**
 * Tests generate pot command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.4
 */
class GeneratePotTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = FRAMEWORK_PATH.'/environment/assets/lang/';
    /**
     * Test resulting message.
     */
    public function testResultMessage()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/assets/lang/my-app.pot';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' generate pot');
        // Assert
        $this->assertEquals('POT file generated!', $execution);
        $this->assertFileExists($filename);
    }
    /**
     * Test resulting message.
     */
    public function testMultidomainGeneration()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/assets/lang/other-domain.pot';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' generate pot other-domain');
        // Assert
        $this->assertEquals('POT file generated!', $execution);
        $this->assertFileExists($filename);
    }
}