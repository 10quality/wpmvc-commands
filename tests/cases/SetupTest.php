<?php
/**
 * Tests Setup command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.6
 */
class SetupTest extends AyucoTestCase
{
    /**
     * Tests missing setname command error.
     */
    public function test()
    {
        $execution = exec('php '.WPMVC_AYUCO.' setup');

        $this->assertEquals('SetupCommand: "setname" command is not registered in ayuco.', $execution);
    }
}