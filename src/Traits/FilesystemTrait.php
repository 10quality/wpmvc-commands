<?php

namespace WPMVC\Commands\Traits;

/**
 * Filesystem utility trait..
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.12
 */
trait FilesystemTrait
{
    /**
     * Creates a path.
     * @since 1.1.12
     */
    protected function createPath($path)
    {
        if (!is_dir($path))
            mkdir($path, 0777, true);
    }

}