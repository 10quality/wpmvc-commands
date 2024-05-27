<?php

use Gettext\Loader\PoLoader;

/**
 * Tests generate pot command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.18
 */
class GenerateLangExclusionsTest extends WpmvcAyucoTestCase
{
    /**
     * Temporary hold for bootstrap config.
     */
    protected $configBackup;
    /**
     * paths to be unlinked during Teardown.
     */
    protected $path = [
        FRAMEWORK_PATH.'/environment/assets/lang/',
        FRAMEWORK_PATH.'/environment/assets/views/',
        FRAMEWORK_PATH.'/environment/assets/js/',
        FRAMEWORK_PATH.'/environment/assets/',
    ];
    /**
     * Run before tests.
     */
    public function setUp(): void
    {
        // Make backup of current config
        $config = TESTING_PATH.'/app/Config/app.php';
        $this->configBackup = file_get_contents($config);
        // Make other files
        if (!is_dir(TESTING_PATH.'/assets/views/'))
            mkdir(TESTING_PATH.'/assets/views/', 0777, true);
        if (!is_file(TESTING_PATH.'/assets/views/localize.php'))
            file_put_contents(TESTING_PATH.'/assets/views/localize.php', '<?php echo _e( \'View text\', \'my-app\' ) ?>');
        if (!is_file(TESTING_PATH.'/assets/views/excluded.php'))
            file_put_contents(TESTING_PATH.'/assets/views/excluded.php', '<?php echo _e( \'Excluded view\', \'my-app\' ) ?>');
        if (!is_dir(TESTING_PATH.'/assets/js/'))
            mkdir(TESTING_PATH.'/assets/js/', 0777, true);
        if (!is_file(TESTING_PATH.'/assets/js/excluded.js'))
            file_put_contents(TESTING_PATH.'/assets/js/excluded.js', '__( \'Excluded text\', \'my-app\' );');
        // Override config
        file_put_contents($config, '<?php
            return [
                \'namespace\' => \'MyApp\',
                \'type\' => \'theme\',
                \'version\' => \'1.0.0\',
                \'author\' => \'Developer <developer@wpmvc>\',
                \'paths\' => [
                    \'base\'          => __DIR__ . \'/../\',
                    \'controllers\'   => __DIR__ . \'/../Controllers/\',
                    \'views\'         => __DIR__ . \'/../../assets/views/\',
                ],
                \'localize\' => [
                    \'textdomain\'    => \'my-app\',
                    \'path\'          => __DIR__ . \'/../../assets/lang/\',
                    \'translations\' => [
                        \'file_exclusions\' => [\'excluded.php\', \'excluded.js\'],
                    ],
                ],
            ];'
        );
    }
    /**
     * Restore bootstrap config.
     * @since 
     */
    public function tearDown(): void
    {
        parent::tearDown();
        file_put_contents(TESTING_PATH.'/app/Config/app.php', $this->configBackup);
    }
    /**
     * Test when configuration is set.
     * @group pot
     * @group localization
     */
    public function testPOTExclusion()
    {
        // Prepare
        $loader = new PoLoader;
        $filename = TESTING_PATH.'/assets/lang/my-app.pot';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' generate pot');
        $translations = $loader->loadFile($filename);
        // Assert
        $this->assertEquals('POT file generated!', $execution);
        $this->assertFileExists($filename);
        $this->assertCount(1, $translations);
    }
    /**
     * Test when configuration is set.
     * @group po
     * @group localization
     */
    public function testPOExclusion()
    {
        // Prepare
        $loader = new PoLoader;
        $filename = TESTING_PATH.'/assets/lang/my-app-en_US.po';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' generate po:en_US');
        $translations = $loader->loadFile($filename);
        // Assert
        $this->assertEquals('PO:en_US file generated!', $execution);
        $this->assertFileExists($filename);
        $this->assertCount(1, $translations);
    }
}
