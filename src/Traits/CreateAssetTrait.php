<?php

namespace WPMVC\Commands\Traits;

use Exception;

/**
 * Trait used to create models in a command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.5
 */
trait CreateAssetTrait
{
    /**
     * Creates an asset file.
     * @since 1.1.5
     *
     * @param string $type     Asset type.
     * @param string $filename Filename.
     * @param array  $args     Additional arguments.
     */
    protected function createAsset($type, $filename, $args = null)
    {
        try {
            // Prepare assets
            $path = $this->rootPath.'/assets/raw';
            if (!is_dir($path))
                mkdir($path);
            // Check type
            switch ($type) {
                case 'js':
                    // Prepare path
                    $path .= '/'.$type;
                    if (!is_dir($path))
                        mkdir($path);
                    // Make file
                    $file = $path.'/'.$filename.'.js';
                    if (!file_exists($file)) {
                        file_put_contents( $file, preg_replace(
                            ['/\{0\}/', '/\{1\}/', '/\{2\}/'],
                            [$filename, $this->config['version'], $this->config['localize']['textdomain']],
                            $this->getTemplate( (array_key_exists('template', $args) ? $args['template'] : 'asset').'.js' )
                        ) );
                        // Print created
                        $this->_print('JavaScript asset created!');
                        $this->_lineBreak();
                    } else {
                        // Print exists
                        $this->_print('Asset exists!');
                        $this->_lineBreak();
                    }
                    break;
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