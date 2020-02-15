<?php

namespace WPMVC\Commands\Traits;

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Ayuco\Exceptions\NoticeException;

/**
 * Trait used to set package namespace.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.10
 */
trait SetNamespaceTrait
{
    /**
     * Sets a projects namespace.
     * @since 1.1.8
     *
     * @param string $namespace Package namespace.
     */
    public function setNamespace($namespace)
    {
        try {
            // Check for MVC configuration file
            if (empty($this->configFilename))
                throw new NoticeException('Command "'.$this->key.'": No configuration file found.');

            // Update Namespace in config file
            $currentnamespace = $this->config['namespace'];
            $this->replaceInFile($currentnamespace, $namespace, $this->configFilename);
            $this->config = include $this->configFilename;
            // Update Namespace in composer.json
            $this->replaceInFile($currentnamespace, $namespace, $this->rootPath.'/composer.json');

            $dir = new RecursiveDirectoryIterator($this->rootPath . '/app', RecursiveDirectoryIterator::SKIP_DOTS);
            foreach (new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST) as $filename => $item) {
                if ($item->isDir())
                    continue;
                $this->replaceInFile( 
                    'namespace ' . $currentnamespace,
                    'namespace ' . $namespace,
                    $filename
                );
                $this->replaceInFile( 
                    'use ' . $currentnamespace,
                    'use ' . $namespace,
                    $filename
                );
            }
            // Print end
            $this->_print('Namespace updated!');
            $this->_lineBreak();

            // Dump Composer Autoload
            if (file_exists($this->rootPath . '/composer.json'))
                exec( 'composer dump-autoload --no-plugins' );
        } catch (Exception $e) {
            file_put_contents(
                $this->rootPath.'/error_log',
                $e->getMessage()
            );
            throw new NoticeException('Command "'.$this->key.'": Fatal error occurred.');
        }
    }
}