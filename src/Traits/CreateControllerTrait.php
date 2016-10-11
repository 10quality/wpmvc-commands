<?php

namespace WPMVC\Commands\Traits;

use Ayuco\Exceptions\NoticeException;

/**
 * Trait used to create views in a commad.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.0
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
            if (!file_exists($filename))
                file_put_contents(
                    $filename,
                    preg_replace(
                        ['/\{0\}/', '/\{1\}/'],
                        [$this->config['namespace'], $name],
                        $this->getTemplate('controller.php')
                    )
                );
            // Print end
            $this->_print('Controller created!');
            $this->_lineBreak();
        } catch (Exception $e) {
            file_put_contents(
                $this->rootPath.'/error_log',
                $e->getMessage()
            );
            throw new NoticeException('Command "'.$this->key.'": Fatal error ocurred.');
        }
    }
}