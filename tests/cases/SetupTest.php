<?php
/**
 * Tests MVC Views.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\MVC
 * @version 1.0.0
 */
class SetupTest extends AyucoTestCase
{
    /**
     * Tests missing setname command error.
     */
    public function test()
    {
        $this->assertCommand('setup', '------------------------------');
    }
}