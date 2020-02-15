<?php
/**
 * Tests create command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.10
 */
class CreateAssetTest extends WpmvcAyucoTestCase
{
    /**
     * Tests path.
     */
    protected $path = [
        FRAMEWORK_PATH.'/environment/assets/raw/sass/parts',
        FRAMEWORK_PATH.'/environment/assets/raw/js',
        FRAMEWORK_PATH.'/environment/assets/raw/css',
        FRAMEWORK_PATH.'/environment/assets/raw/sass',
        FRAMEWORK_PATH.'/environment/assets/raw',
        FRAMEWORK_PATH.'/environment/assets',
    ];
    /**
     * Tests javascript asset creation.
     * @group assets
     * @group js
     */
    public function testJs()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/assets/raw/js/yolo.js';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' create js:yolo');
        // Assert
        $this->assertEquals('js asset created!', $execution);
        $this->assertFileExists($filename);
    }
    /**
     * Tests jquery asset creation.
     * @group assets
     * @group js
     */
    public function testJsJquery()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/assets/raw/js/jquery.yolo.js';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' create js:jquery.yolo jquery');
        // Assert
        $this->assertEquals('js asset created!', $execution);
        $this->assertFileExists($filename);
        $this->assertPregMatchContents('/jQuery/', $filename);
    }
    /**
     * Tests css asset creation.
     * @group assets
     * @group css
     */
    public function testCss()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/assets/raw/css/yolo.css';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' create css:yolo');
        // Assert
        $this->assertEquals('css asset created!', $execution);
        $this->assertFileExists($filename);
    }
    /**
     * Tests sass master asset creation.
     * @group assets
     * @group sass
     */
    public function testSassMaster()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/assets/raw/sass/styles.sass';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' create sass:styles');
        // Assert
        $this->assertEquals('sass asset created!', $execution);
        $this->assertFileExists($filename);
        $this->assertPregMatchContents('/master/', $filename);
    }
    /**
     * Tests sass partial asset creation.
     * @group assets
     * @group sass
     */
    public function testSassPart()
    {
        // Prepare
        $masterfile = FRAMEWORK_PATH.'/environment/assets/raw/sass/main.sass';
        $partfile = FRAMEWORK_PATH.'/environment/assets/raw/sass/parts/_theme.sass';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' create sass:theme main');
        // Assert
        $this->assertEquals('sass asset created!', $execution);
        $this->assertFileExists($masterfile);
        $this->assertFileExists($partfile);
        $this->assertPregMatchContents('/master/', $masterfile);
        $this->assertPregMatchContents('/\@import(|\s)parts\/theme\;/', $masterfile);
    }
    /**
     * Tests sass master and partials asset creation.
     * @group assets
     * @group sass
     */
    public function testSassMasterAndParts()
    {
        // Prepare
        $masterfile = FRAMEWORK_PATH.'/environment/assets/raw/sass/theme.sass';
        $part1file = FRAMEWORK_PATH.'/environment/assets/raw/sass/parts/_header.sass';
        $part2file = FRAMEWORK_PATH.'/environment/assets/raw/sass/parts/_footer.sass';
        // Execute
        exec('php '.WPMVC_AYUCO.' create sass:theme');
        exec('php '.WPMVC_AYUCO.' create sass:header theme');
        $execution = exec('php '.WPMVC_AYUCO.' create sass:footer theme');
        // Assert
        $this->assertEquals('sass asset created!', $execution);
        $this->assertFileExists($masterfile);
        $this->assertFileExists($part1file);
        $this->assertFileExists($part2file);
        $this->assertPregMatchContents('/\@import(|\s)parts\/header\;/', $masterfile);
        $this->assertPregMatchContents('/\@import(|\s)parts\/footer\;/', $masterfile);
    }
    /**
     * Tests sass gitignore update.
     * @group assets
     * @group sass
     */
    public function testSassGitignore()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/.gitignore';
        // Execute
        exec('php '.WPMVC_AYUCO.' create sass:theme');
        // Assert
        $this->assertFileExists($filename);
        $this->assertPregMatchContents('/\# SASS COMPILATION/', $filename);
        unlink($filename);
    }
    /**
     * Tests asset registration prevention.
     * @group assets
     * @group duplication
     */
    public function testPreventAssetRegistration()
    {
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' register asset:css/test.css');
        // Assert
        $this->assertEquals('Asset doesn\'t exist!', $execution);
    }
    /**
     * Tests sass master asset creation.
     * @group assets
     * @group scss
     */
    public function testScssMaster()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/assets/raw/sass/styles.scss';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' create scss:styles');
        // Assert
        $this->assertEquals('scss asset created!', $execution);
        $this->assertFileExists($filename);
        $this->assertPregMatchContents('/master/', $filename);
    }
    /**
     * Tests sass partial asset creation.
     * @group assets
     * @group scss
     */
    public function testScssPart()
    {
        // Prepare
        $masterfile = FRAMEWORK_PATH.'/environment/assets/raw/sass/main.scss';
        $partfile = FRAMEWORK_PATH.'/environment/assets/raw/sass/parts/_theme.scss';
        // Execute
        $execution = exec('php '.WPMVC_AYUCO.' create scss:theme main');
        // Assert
        $this->assertEquals('scss asset created!', $execution);
        $this->assertFileExists($masterfile);
        $this->assertFileExists($partfile);
        $this->assertPregMatchContents('/master/', $masterfile);
        $this->assertPregMatchContents('/\@import(|\s)\\\'parts\/theme\\\'\;/', $masterfile);
    }
    /**
     * Tests sass master and partials asset creation.
     * @group assets
     * @group scss
     */
    public function testScssMasterAndParts()
    {
        // Prepare
        $masterfile = FRAMEWORK_PATH.'/environment/assets/raw/sass/theme.scss';
        $part1file = FRAMEWORK_PATH.'/environment/assets/raw/sass/parts/_header.scss';
        $part2file = FRAMEWORK_PATH.'/environment/assets/raw/sass/parts/_footer.scss';
        // Execute
        exec('php '.WPMVC_AYUCO.' create scss:theme');
        exec('php '.WPMVC_AYUCO.' create scss:header theme');
        $execution = exec('php '.WPMVC_AYUCO.' create scss:footer theme');
        // Assert
        $this->assertEquals('scss asset created!', $execution);
        $this->assertFileExists($masterfile);
        $this->assertFileExists($part1file);
        $this->assertFileExists($part2file);
        $this->assertPregMatchContents('/\@import(|\s)\\\'parts\/header\\\'\;/', $masterfile);
        $this->assertPregMatchContents('/\@import(|\s)\\\'parts\/footer\\\'\;/', $masterfile);
    }
}
