<?php

namespace WPMVC\Commands\Traits;

use Exception;
use Ayuco\Exceptions\NoticeException;

/**
 * Trait used to create WordPress blocks in a command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.2.0
 */
trait CreateBlockTrait
{
    /**
     * Creates an block files.
     * @since 1.2.0
     *
     * @param string $name Block name.
     * @param array  $args Additional arguments.
     */
    protected function createBlock($name, $args = [])
    {
        try {
            // Prepare assets
            $path = $this->rootPath.'/assets/raw/blocks/' . $name;
            if (!is_dir($path))
                mkdir($path, 0777, true);
            // Check type
            $file = $path . '/block.jsx';
            if (!file_exists($file)) {
                file_put_contents($file, preg_replace(
                    ['/\{0\}/', '/\{1\}/', '/\{2\}/', '/\{3\}/', '/\{4\}/'],
                    [
                        $name,
                        $this->config['version'],
                        $this->config['localize']['textdomain'],
                        array_key_exists('author', $this->config) ? $this->config['author'] : '',
                    ],
                    $this->getTemplate('block.jsx')
                ));
                // Print created
                $this->_print('Block script created!');
                $this->_lineBreak();
            } else {
                // Print exists
                $this->_print('Block script exists!');
                $this->_lineBreak();
            }
            if (!array_key_exists('nostyle', $this->options)) {
                $file = $path . '/style.scss';
                if (!file_exists($file)) {
                    file_put_contents($file, preg_replace(
                        ['/\{0\}/', '/\{1\}/', '/\{2\}/', '/\{3\}/'],
                        [
                            $name . ' style (front-end)',
                            $this->config['version'],
                            $this->config['localize']['textdomain'],
                            array_key_exists('author', $this->config) ? $this->config['author'] : '',
                        ],
                        $this->getTemplate('master.scss')
                    ));
                    // Print created
                    $this->_print('Block style (front-end) created!');
                    $this->_lineBreak();
                } else {
                    // Print exists
                    $this->_print('Block style exists!');
                    $this->_lineBreak();
                }
            }
            if (!array_key_exists('noeditor', $this->options)) {
                $file = $path . '/editor.scss';
                if (!file_exists($file)) {
                    file_put_contents($file, preg_replace(
                        ['/\{0\}/', '/\{1\}/', '/\{2\}/', '/\{3\}/'],
                        [
                            $name . ' editor (back-end)',
                            $this->config['version'],
                            $this->config['localize']['textdomain'],
                            array_key_exists('author', $this->config) ? $this->config['author'] : '',
                        ],
                        $this->getTemplate('master.scss')
                    ));
                    // Print created
                    $this->_print('Block editor (back-end) created!');
                    $this->_lineBreak();
                } else {
                    // Print exists
                    $this->_print('Block editor exists!');
                    $this->_lineBreak();
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