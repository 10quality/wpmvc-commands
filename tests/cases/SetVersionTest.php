<?php
/**
 * Tests set version command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.6
 */
class SetVersionTest extends AyucoTestCase
{
    /**
     * Test resulting message.
     */
    public function testResultMessage()
    {
        $execution = exec('php '.WPMVC_AYUCO.' set version:1.5.0');

        $this->assertEquals('Version updated!', $execution);
    }
    /**
     * Test if version value has been changed.
     */
    public function testVersionValue()
    {
        $execution = exec('php '.WPMVC_AYUCO.' set version:2.0.0');
        $json = json_decode(file_get_contents(FRAMEWORK_PATH.'/environment/package.json'));

        $this->assertEquals('2.0.0', $json->version);

        // Down test
        exec('php '.WPMVC_AYUCO.' set version:1.0.0');
    }
    /**
     * Test if theme version has been changed.
     */
    public function testThemeVersionValue()
    {
        $execution = exec('php '.WPMVC_AYUCO.' set version:1.7.5');
        preg_match(
            '/[Vv]ersion\:[|\s][0-9\.vV]+/',
            file_get_contents(FRAMEWORK_PATH.'/environment/style.css'),
            $matches
        );

        $this->assertEquals('Version: 1.7.5', $matches[0]);

        // Down test
        exec('php '.WPMVC_AYUCO.' set version:1.0.0');
    }
}