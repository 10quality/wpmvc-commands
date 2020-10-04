<?php

namespace WPMVC\Commands\Traits;

use Exception;
use WPMVC\Commands\Core\Builder;
use Ayuco\Exceptions\NoticeException;
use WPMVC\Commands\Visitors\AddClassMethodVisitor;

/**
 * Trait used to create a test case.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.12
 */
trait CreateTestTrait
{
    /**
     * Creates a test case.
     * @since 1.0.12
     *
     * @param string $name Test name.
     * @param array  $args Command arguments.
     */
    protected function createTest($name, $args = [])
    {
        try {
            // Prepare
            $path = $this->rootPath.'/tests/cases';
            // Directory
            if (!is_dir($path))
                mkdir($path);
            // Test file
            $filename = $path.'/'.$name.'Test.php';
            if (!file_exists($filename)) {
                file_put_contents(
                    $filename,
                    preg_replace(
                        ['/\{0\}/', '/\{1\}/', '/\{2\}/', '/\{3\}/'],
                        [
                            $name,
                            array_key_exists('author', $this->config) ? $this->config['author'] : '',
                            $this->config['localize']['textdomain'],
                            $this->config['version']
                        ],
                        $this->getTemplate('testcase.php')
                    )
                );
                // Print created
                $this->_print('Test case created!');
                $this->_lineBreak();
            } else {
                // Print exists
                $this->_print('Test case exists!');
                $this->_lineBreak();
            }
        } catch (Exception $e) {
            file_put_contents(
                $this->rootPath.'/error_log',
                $e->getMessage()
            );
            throw new NoticeException('Command "'.$this->key.'": Fatal error occurred.');
        }
    }

    /**
     * Creates a controller method.
     * @since 1.0.0
     *
     * @param string $controller Controller name.
     * @param string $name       Method name.
     * @param array  $params     Method parameters.
     * @param string $comment    Method comment.
     */
    protected function createTestMethod($test, $method, $params = [], $comment = '')
    {
        $this->updateBuffer();
        $filename = $this->rootPath.'/tests/cases/'.$test.'Test.php';
        if (!$this->existsFunctionIn($filename, $method)) {
            try {
                $this->config['_options'] = $this->options;
                $builder = Builder::parser($filename, array_key_exists('nopretty', $this->options));
                $builder->addVisitor(new AddClassMethodVisitor($this->config, $method, $params, $comment));
                $builder->build();
                // Update class version
                $this->updateComment('version', $this->config['version'], $filename);
            } catch (Exception $e) {
                file_put_contents(
                    $this->rootPath.'/error_log',
                    $e->getMessage()
                );
                throw new NoticeException('Command "'.$this->key.'": Fatal error occurred.');
            }
        } else {
            // Print exists
            $this->_print('Test method exists!');
            $this->_lineBreak();
        }
    }
}