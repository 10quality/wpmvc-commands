<?php
/**
 * Tests set domain command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.10
 */
class SetDomainTest extends AyucoTestCase
{
    /**
     * Test resulting message.
     * @group domain
     */
    public function testResultMessage()
    {
        $execution = exec('php '.WPMVC_AYUCO.' set domain:phpunit');

        $this->assertEquals($execution, 'Text domain updated!');
        // Down test
        exec('php '.WPMVC_AYUCO.' set domain:my-app');
    }
    /**
     * Test package.json.
     * @group domain
     */
    public function testPackageDomainValue()
    {
        // Run
        $execution = exec('php '.WPMVC_AYUCO.' set domain:domain-value');
        $json = json_decode(file_get_contents(FRAMEWORK_PATH.'/environment/package.json'));
        // Asset
        $this->assertEquals('domain-value', $json->name);
        // Down test
        exec('php '.WPMVC_AYUCO.' set domain:my-app');
    }
    /**
     * Test composer.json.
     * @group domain
     */
    public function testComposerDomainValue()
    {
        // Run
        $execution = exec('php '.WPMVC_AYUCO.' set domain:domain-value');
        $json = json_decode(file_get_contents(FRAMEWORK_PATH.'/environment/composer.json'));
        // Asset
        $this->assertEquals('wpmvc/domain-value', $json->name);
        // Down test
        exec('php '.WPMVC_AYUCO.' set domain:my-app');
    }
    /**
     * Test style.css.
     * @group domain
     */
    public function testThemeDomainValue()
    {
        $execution = exec('php '.WPMVC_AYUCO.' set domain:special-domain');
        preg_match(
            '/[Tt]ext\s[Dd]omain\:[|\s][a-z-A-Z-0-9\-\.\[\]]+.*/',
            file_get_contents(FRAMEWORK_PATH.'/environment/style.css'),
            $matches
        );

        $this->assertEquals(1, preg_match('/special\-domain/', $matches[0]));
        // Down test
        exec('php '.WPMVC_AYUCO.' set domain:my-app');
    }
    /**
     * Test missing domain.
     * @group domain
     */
    public function testMissingDomain()
    {
        // Prepare & run
        $execution = exec('php '.WPMVC_AYUCO.' set domain');
        // Run
        $this->assertEquals('Command "set": Expecting a text domain.', $execution);
    }
}
