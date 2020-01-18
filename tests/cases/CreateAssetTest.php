<?php
/**
 * Tests create command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.7
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
    ];
    /**
     * Test.
     */
    public function testJs()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/assets/raw/js/yolo.js';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' create js:yolo');
        // Assert
        $this->assertEquals('js asset created!', $execution);
        $this->assertFileExists($filename);
    }
    /**
     * Test.
     */
    public function testJsJquery()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/assets/raw/js/jquery.yolo.js';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' create js:jquery.yolo jquery');
        // Assert
        $this->assertEquals('js asset created!', $execution);
        $this->assertFileExists($filename);
        $this->assertPregMatchContents('/jQuery/', $filename);
    }
    /**
     * Test.
     */
    public function testCss()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/assets/raw/css/yolo.css';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' create css:yolo');
        // Assert
        $this->assertEquals('css asset created!', $execution);
        $this->assertFileExists($filename);
    }
    /**
     * Test.
     */
    public function testSassMaster()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/assets/raw/sass/styles.scss';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' create sass:styles');
        // Assert
        $this->assertEquals('scss asset created!', $execution);
        $this->assertFileExists($filename);
        $this->assertPregMatchContents('/master/', $filename);
    }
    /**
     * Test.
     */
    public function testSassPart()
    {
        // Prepare
        $masterfile = FRAMEWORK_PATH.'/environment/assets/raw/sass/main.scss';
        $partfile = FRAMEWORK_PATH.'/environment/assets/raw/sass/parts/_theme.scss';
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' create sass:theme main');
        // Assert
        $this->assertEquals('scss asset created!', $execution);
        $this->assertFileExists($masterfile);
        $this->assertFileExists($partfile);
        $this->assertPregMatchContents('/master/', $masterfile);
        $this->assertPregMatchContents('/\@import(|\s)\\\'parts\/theme\\\'\;/', $masterfile);
    }
    /**
     * Test.
     */
    public function testSassMasterAndParts()
    {
        // Prepare
        $masterfile = FRAMEWORK_PATH.'/environment/assets/raw/sass/theme.scss';
        $part1file = FRAMEWORK_PATH.'/environment/assets/raw/sass/parts/_header.scss';
        $part2file = FRAMEWORK_PATH.'/environment/assets/raw/sass/parts/_footer.scss';
        // Execure
        exec('php '.WPMVC_AYUCO.' create sass:theme');
        exec('php '.WPMVC_AYUCO.' create sass:header theme');
        $execution = exec('php '.WPMVC_AYUCO.' create sass:footer theme');
        // Assert
        $this->assertEquals('scss asset created!', $execution);
        $this->assertFileExists($masterfile);
        $this->assertFileExists($part1file);
        $this->assertFileExists($part2file);
        $this->assertPregMatchContents('/\@import(|\s)\\\'parts\/header\\\'\;/', $masterfile);
        $this->assertPregMatchContents('/\@import(|\s)\\\'parts\/footer\\\'\;/', $masterfile);
    }
    /**
     * Test.
     */
    public function testSassGitignore()
    {
        // Prepare
        $filename = FRAMEWORK_PATH.'/environment/.gitignore';
        // Execure
        exec('php '.WPMVC_AYUCO.' create sass:theme');
        // Assert
        $this->assertFileExists($filename);
        $this->assertPregMatchContents('/\# SASS COMPILATION/', $filename);
        unlink($filename);
    }
    /**
     * Test.
     */
    public function testPreventAssetRegistration()
    {
        // Execure
        $execution = exec('php '.WPMVC_AYUCO.' register asset:css/test.css');
        // Assert
        $this->assertEquals('Asset doesn\'t exist!', $execution);
    }
}