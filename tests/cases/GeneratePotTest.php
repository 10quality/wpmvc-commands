<?php
/**
 * Tests generate pot command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.8
 */
class GeneratePotTest extends WpmvcAyucoTestCase
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
    public function setUp()
    {
        if (!is_dir(TESTING_PATH.'/assets/views/'))
            mkdir(TESTING_PATH.'/assets/views/', 0777, true);
        if (!is_dir(TESTING_PATH.'/app/Localize/'))
            mkdir(TESTING_PATH.'/app/Localize/', 0777, true);
        if (!is_file(TESTING_PATH.'/assets/views/localize.php'))
            file_put_contents(TESTING_PATH.'/assets/views/localize.php', '<?php echo _e( \'View text 1\', \'my-app\' ) ?>');
        if (!is_file(TESTING_PATH.'/app/Localize/Test.php'))
            file_put_contents(TESTING_PATH.'/app/Localize/Test.php', '<?php namespace MyApp\Localize;'
                . ' class Test { public function __construct() { $assign = __( \'Test assign variable\', \'my-app\' );'
                . ' $dquotes = __( "Double quotes", "my-app" ); $unspaced = __(\'Test assign variable\', \'my-app\');'
                . ' _e( \'Test echoed string "Yolo"\', \'my-app\' ); $numeric = _n( \'One string\', \'%d strings\', 3, \'my-app\' );'
                . ' _e( \'Other domain\', \'other-domain\' );}}'
            );
    }
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