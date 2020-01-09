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
 * @version 1.0.0
 */
trait CreateModelTrait
{
    /**
     * Creates a model.
     * @since 1.0.0
     *
     * @param string $name Model name.
     */
    protected function createModel($name, $type = 'PostModel')
    {
        try {
            // Prepare
            $path = $this->rootPath.'/app/Models';
            // Directory
            if (!is_dir($path))
                mkdir($path);
            // Controller file
            $filename = $path.'/'.$name.'.php';
            if (!file_exists($filename))
                file_put_contents(
                    $filename,
                    preg_replace(
                        ['/\{0\}/', '/\{1\}/', '/\{2\}/'],
                        [$this->config['namespace'], $type, $name],
                        $this->getTemplate('model.php')
                    )
                );
            // Print end
            $this->_print('Model created!');
            $this->_lineBreak();
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
        try {
            $builder = Builder::parser($this->rootPath.'/app/Models/'.$model.'.php');
            $builder->addVisitor(new AddClassMethodVisitor($method, $params, $comment));
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
        try {
            $builder = Builder::parser($this->rootPath.'/app/Models/'.$model.'.php');
            $builder->addVisitor(new AddClassPropertyVisitor($property, $value, $type, $comment));
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