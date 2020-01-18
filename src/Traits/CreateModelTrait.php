<?php

namespace WPMVC\Commands\Traits;

use Exception;
use WPMVC\Commands\Core\Builder;
use Ayuco\Exceptions\NoticeException;
use WPMVC\Commands\Visitors\AddClassMethodVisitor;
use WPMVC\Commands\Visitors\AddClassPropertyVisitor;

/**
 * Trait used to create models in a command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.6
 */
trait CreateModelTrait
{
    /**
     * Creates a model.
     * @since 1.0.0
     *
     * @param string $name  Model name.
     * @param string $type  Model type.
     * @param string $trait Find trait class.
     */
    protected function createModel($name, $type = 'PostModel', $trait = 'FindTrait')
    {
        try {
            // Prepare
            $path = $this->rootPath.'/app/Models';
            // Directory
            if (!is_dir($path))
                mkdir($path);
            // Controller file
            $filename = $path.'/'.$name.'.php';
            if (!file_exists($filename)) {
                file_put_contents(
                    $filename,
                    preg_replace(
                        ['/\{0\}/', '/\{1\}/', '/\{2\}/', '/\{3\}/', '/\{4\}/', '/\{5\}/', '/\{6\}/'],
                        [
                            $this->config['namespace'],
                            $type,
                            $name,
                            $trait,
                            array_key_exists('author', $this->config) ? $this->config['author'] : '',
                            $this->config['localize']['textdomain'],
                            $this->config['version'],
                        ],
                        $this->getTemplate('model.php')
                    )
                );
                // Print created
                $this->_print('Model created!');
                $this->_lineBreak();
            } else {
                // Print exists
                $this->_print('Model exists!');
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
     * @param string $model   Model name.
     * @param string $name    Method name.
     * @param array  $params  Method parameters.
     * @param string $comment Method comment.
     */
    protected function createModelMethod($model, $method, $params = [], $comment = '')
    {
        $this->updateBuffer();
        $filename = $this->rootPath.'/app/Models/'.$model.'.php';
        if (!$this->existsFunctionIn($filename, $method)) {
            try {
                $builder = Builder::parser($filename);
                $builder->addVisitor(new AddClassMethodVisitor($this->config, $method, $params, $comment));
                $builder->build();
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
     * Creates a controller property.
     * @since 1.0.0
     *
     * @param string $model   Model name.
     * @param string $name    Name.
     * @param mixed  $value   Value.
     * @param int    $type    Type (public, private or protected)
     * @param string $comment Comment.
     */
    protected function createModelProperty($model, $property, $value = null, $type = 2, $comment = '')
    {
        $this->updateBuffer();
        $filename = $this->rootPath.'/app/Models/'.$model.'.php';
        if (!$this->existsPropertyIn($filename, $property, $type)) {
            try {
                $builder = Builder::parser($filename);
                $builder->addVisitor(new AddClassPropertyVisitor($this->config, $property, $value, $type, $comment));
                $builder->build();
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