<?php

/**
 * Improved ayuco test case for WordPress MVC.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.5
 */
class WpmvcAyucoTestCase extends AyucoTestCase
{
    /**
     * Identifies the folder path in which new folder/files will be created.
     * @since 1.1.4
     * 
     * @var string
     */
    protected $path = null;
    /**
     * Clear path.
     * @since 1.1.4
     */
    public function tearDown(): void
    {
        if (!isset($this->path) || empty($this->path))
            return;
        if (!is_array($this->path))
            $this->path = array($this->path);
        foreach ($this->path as $path) {
            if (!is_dir($path))
                continue;
            $dir = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
            foreach (new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST) as $filename => $item) {
                if ($item->isDir()) {
                    rmdir($filename);
                } else {
                    unlink($filename);
                }
            }
            rmdir($path);
        }
    }
    /**
     * Asserts if a regular expresion matches inside file contents.
     * @since 1.1.4
     *
     * @param string $regex    Regular expression.
     * @param string $filename Fulename
     * @param string $message  PHPUNIT message.
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function assertPregMatchContents($regex, $filename, $message = 'Failed asserting matching contents.')
    {
        if (!is_file($filename))
            throw new PHPUnit_Framework_AssertionFailedError('Filename doesn\'t exists');
        $contents = file_get_contents($filename);
        self::assertThat(
            preg_match($regex, $contents) == 1,
            self::isTrue(),
            $message
        );
    }
    /**
     * Asserts if a class method exists.
     * @since 1.1.4
     *
     * @param string $method   Method name.
     * @param string $filename Fulename
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function assertFileFunctionExists($method, $filename)
    {
        $this->assertPregMatchContents('/function(|\s)'.$method.'\(/', $filename, 'Failed asserting method existence.');
    }
    /**
     * Asserts if a class method exists.
     * @since 1.1.4
     *
     * @param string $method   Method name.
     * @param string $filename Fulename
     *
     * @throws PHPUnit_Framework_AssertionFailedError
     */
    public function assertFileVariableExists($property, $filename, $value = null)
    {
        $regex = '/\$'.$property;
        if (!empty($value))
            $regex .= '(|\s)=(|\s)' . (is_string($value) && $value !== 'true' && $value !== 'false' ? ('\''.$value.'\'') : $value);
        $regex .= '/';
        $this->assertPregMatchContents($regex, $filename, 'Failed asserting variable existence.');
    }
}