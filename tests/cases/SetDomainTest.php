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
class SetDomainTest extends WpmvcAyucoTestCase
{
    /**
     * Retore to default namespace.
     * @since 1.1.10
     */
    public function tearDown(): void
    {
        parent::tearDown();
        exec('php '.WPMVC_AYUCO.' set domain:my-app');
    }
    /**
     * Test resulting message.
     * @group domain
     */
    public function testResultMessage()
    {
        $execution = exec('php '.WPMVC_AYUCO.' set domain:phpunit');
        // Assert
        $this->assertEquals($execution, 'Text domain updated!');
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
        // Assert
        $this->assertEquals('domain-value', $json->name);
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
        // Assert
        $this->assertEquals('wpmvc/domain-value', $json->name);
    }
    /**
     * Test style.css.
     * @group domain
     */
    public function testThemeDomainValue()
    {
        // Prepare
        $execution = exec('php '.WPMVC_AYUCO.' set domain:special-domain');
        preg_match(
            '/[Tt]ext\s[Dd]omain\:[|\s][a-z-A-Z-0-9\-\.\[\]]+.*/',
            file_get_contents(FRAMEWORK_PATH.'/environment/style.css'),
            $matches
        );
        $this->assertEquals(1, preg_match('/special\-domain/', $matches[0]));
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
    /**
     * Test that namespace is un changed during doman change.
     * @group domain
     * @group namespace
     */
    public function testNamespacePreservation()
    {
        // Prepare
        $filename = TESTING_PATH.'/app/Config/app.php';
        // Run
        exec('php '.WPMVC_AYUCO.' set domain:namespace');
        exec('php '.WPMVC_AYUCO.' set namespace:Preserve');
        // Assert
        $this->assertStringMatchContents('textdomain\' => \'namespace\'', $filename);
        $this->assertStringMatchContents('namespace\' => \'Preserve\'', $filename);
    }
}
