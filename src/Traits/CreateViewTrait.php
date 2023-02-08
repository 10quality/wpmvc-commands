<?php

namespace WPMVC\Commands\Traits;

use Exception;
use Ayuco\Exceptions\NoticeException;

/**
 * Trait used to create views in a command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.17
 */
trait CreateViewTrait
{
    /**
     * Creates a view.
     * @since 1.0.0
     *
     * @param string $key      View key/name.
     * @param array  $template Template to use.
     */
    protected function createView($key, $template = 'view.php')
    {
        try {
            // Prepare
            $path = $this->rootPath.'/assets/views';
            if (!is_dir($path))
                mkdir($path, 0777, true);
            $views = explode('.', $key);
            // Loop creation
            for ($i = 0; $i < count($views); ++$i) {
                if (count($views) - 1 == $i) {
                    // File check
                    $filename = $path.'/'.$views[$i].'.php';
                    if (!file_exists($filename)) {
                        file_put_contents(
                            $filename,
                            preg_replace(
                                ['/\{0\}/', '/\{1\}/', '/\{2\}/', '/\{3\}/'],
                                [
                                    $key,
                                    array_key_exists('author', $this->config) ? $this->config['author'] : '',
                                    $this->config['localize']['textdomain'],
                                    $this->config['version'],
                                ],
                                $this->getTemplate($template))
                        );
                        // Print created
                        $this->_print_success('View created!');
                        $this->_lineBreak();
                    } else {
                        // Print exists
                        $this->_print_info('View exists!');
                        $this->_lineBreak();
                    }
                } else {
                    // Directory check
                    $path .= '/'.$views[$i];
                    if (!is_dir($path))
                        mkdir($path, 0777, true);
                }
            }
        } catch (Exception $e) {
            file_put_contents(
                $this->rootPath.'/error_log',
                $e->getMessage()
            );
            throw new NoticeException('Command "'.$this->key.'": Fatal error occurred.');
        }
    }
}