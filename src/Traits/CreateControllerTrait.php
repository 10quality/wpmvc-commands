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
 * @version 1.1.6
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
                        ['/\{0\}/', '/\{1\}/', '/\{2\}/', '/\{3\}/', '/\{4\}/'],
                        [
                            $this->config['namespace'],
                            $name,
                            array_key_exists('author', $this->config) ? $this->config['author'] : '',
                            $this->config['localize']['textdomain'],
                            $this->config['version'],
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
        try {
            $builder = Builder::parser($this->rootPath.'/app/Controllers/'.$controller.'.php');
            $builder->addVisitor(new AddClassMethodVisitor($this->config, $method, $params, $comment));
            $builder->build();
        } catch (Exception $e) {
            file_put_contents(
                $this->rootPath.'/error_log',
                $e->getMessage()
            );
            throw new NoticeException('Command "'.$this->key.'": Fatal error occurred.');
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
                        ['/\{0\}/', '/\{1\}/', '/\{2\}/', '/\{3\}/', '/\{4\}/'],
                        [
                            $this->config['namespace'],
                            $name,
                            array_key_exists('author', $this->config) ? $this->config['author'] : '',
                            $this->config['localize']['textdomain'],
                            $this->config['version'],
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
        try {
            $builder = Builder::parser($this->rootPath.'/app/Controllers/'.$controller.'.php');
            $builder->addVisitor(new AddClassPropertyVisitor($this->config, $property, $value, $type, $comment));
            $builder->build();
        } catch (Exception $e) {
            file_put_contents(
                $this->rootPath.'/error_log',
                $e->getMessage()
            );
            throw new NoticeException('Command "'.$this->key.'": Fatal error occurred.');
        }
    }
}