<?php

use Gettext\Generator\PoGenerator;
use Gettext\Loader\MoLoader;
use Gettext\Loader\PoLoader;

/**
 * Tests generate po command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.17
 */
class GenerateMoTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = [
        FRAMEWORK_PATH.'/environment/assets/lang/',
        FRAMEWORK_PATH.'/environment/assets/views/',
        FRAMEWORK_PATH.'/environment/assets/',
        FRAMEWORK_PATH.'/environment/app/Localize/',
    ];
    /**
     * Run before tests.
     */
    public function setUp(): void
    {
        if (!is_dir(TESTING_PATH.'/assets/views/'))
            mkdir(TESTING_PATH.'/assets/views/', 0777, true);
        if (!is_dir(TESTING_PATH.'/app/Localize/'))
            mkdir(TESTING_PATH.'/app/Localize/', 0777, true);
        if (!is_file(TESTING_PATH.'/assets/views/localize.php'))
            file_put_contents(TESTING_PATH.'/assets/views/localize.php', '<?php echo _e( \'View text 1\', \'my-app\' ) ?>');
        if (!is_file(TESTING_PATH.'/app/Localize/Test.php'))
            file_put_contents(TESTING_PATH.'/app/Localize/Test.php', '<?php namespace MyApp\Localize;'
                . ' class Test { public function __construct() { $assign = __( \'Test assign variable\', \'my-app\' );}}'
            );
    }
    /**
     * Test resulting message.
     * @group mo
     * @group localization
     */
    public function testGeneration()
    {
        // Prepare
        $loader = new PoLoader;
        $filename = FRAMEWORK_PATH.'/environment/assets/lang/my-app-es_ES.mo';
        $po_filename = FRAMEWORK_PATH.'/environment/assets/lang/my-app-es_ES.po';
        exec('php '.WPMVC_AYUCO.' generate po:es_ES');
        $translations = $loader->loadFile($po_filename);
        $translation = $translations->find(null, 'View text 1');
        $translation->translate('Ver texto 1');
        $translations = $translations->add($translation);
        $translation = $translations->find(null, 'Test assign variable');
        $translation->translate('Probar assignar variable');
        $translations = $translations->add($translation);
        $generator = new PoGenerator();
        $generator->generateFile($translations, $po_filename);
        $loader = new MoLoader;
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' generate mo:es_ES');
        $translations = $loader->loadFile($filename);
        // Assert
        $this->assertEquals('MO:es_ES file generated!', $execution);
        $this->assertFileExists($filename);
        $this->assertCount(2, $translations);
    }
}