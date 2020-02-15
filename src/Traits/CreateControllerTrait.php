<?php

namespace WPMVC\Commands\Traits;

use Exception;
use WPMVC\Commands\Core\Builder;
use Ayuco\Exceptions\NoticeException;
use WPMVC\Commands\Visitors\AddClassMethodVisitor;
use WPMVC\Commands\Visitors\AddClassPropertyVisitor;

/**
 * Trait used to create views in a command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.10
 */
trait CreateControllerTrait
{
    /**
     * Creates a controller.
     * @since 1.0.0
     *
     * @param string $name Controller name.
     * @param array  $args Command arguments.
     */
    protected function createController($name, $args = [])
    {
        try {
            // Prepare
            $path = $this->rootPath.'/app/Controllers';
            // Directory
            if (!is_dir($path))
                mkdir($path);
            // Controller file
            $filename = $path.'/'.$name.'.php';
            if (!file_exists($filename)) {
                file_put_contents(
                    $filename,
                    preg_replace(
                        ['/\{0\}/', '/\{1\}/', '/\{2\}/', '/\{3\}/', '/\{4\}/', '/\{5\}/'],
                        [
                            $this->config['namespace'],
                            $name,
                            array_key_exists('author', $this->config) ? $this->config['author'] : '',
                            $this->config['localize']['textdomain'],
                            $this->config['version'],
                            array_key_exists('comment', $this->options) ? $this->options['comment'] : $name,
                        ],
                        $this->getTemplate('controller.php')
                    )
                );
                // Print created
                $this->_print('Controller created!');
                $this->_lineBreak();
            } else {
                // Print exists
                $this->_print('Controller exists!');
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
    protected function createControllerMethod($controller, $method, $params = [], $comment = '')
    {
        $this->updateBuffer();
        $filename = $this->rootPath.'/app/Controllers/'.$controller.'.php';
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
            $this->_print('Method exists!');
            $this->_lineBreak();
        }
    }
    /**
     * Creates a model controller.
     * @since 1.0.0
     *
     * @param string $name Controller name.
     */
    protected function createModelController($name)
    {
        try {
            // Prepare
            $path = $this->rootPath.'/app/Controllers';
            // Directory
            if (!is_dir($path))
                mkdir($path);
            // Controller file
            $filename = $path.'/'.$name.'.php';
            if (!file_exists($filename)) {
                file_put_contents(
                    $filename,
                    preg_replace(
                        ['/\{0\}/', '/\{1\}/', '/\{2\}/', '/\{3\}/', '/\{4\}/', '/\{5\}/'],
                        [
                            $this->config['namespace'],
                            $name,
                            array_key_exists('author', $this->config) ? $this->config['author'] : '',
                            $this->config['localize']['textdomain'],
                            $this->config['version'],
                            array_key_exists('comment', $this->options) ? $this->options['comment'] : $name,
                        ],
                        $this->getTemplate('modelcontroller.php')
                    )
                );
                // Print created
                $this->_print('Controller created!');
                $this->_lineBreak();
            } else {
                // Print exists
                $this->_print('Controller exists!');
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
     * Creates a controller property.
     * @since 1.0.0
     *
     * @param string $controller Controller name.
     * @param string $name       Name.
     * @param mixed  $value      Value.
     * @param int    $type       Type (public, private or protected)
     * @param string $comment    Comment.
     */
    protected function createControllerProperty($controller, $property, $value = null, $type = 2, $comment = '')
    {
        $this->updateBuffer();
        $filename = $this->rootPath.'/app/Controllers/'.$controller.'.php';
        if (!$this->existsPropertyIn($filename, $property, $type)) {
            try {
                $this->config['_options'] = $this->options;
                $builder = Builder::parser($filename, array_key_exists('nopretty', $this->options));
                $builder->addVisitor(new AddClassPropertyVisitor($this->config, $property, $value, $type, $comment));
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
            $this->_print('Property exists!');
            $this->_lineBreak();
        }
    }
}