<?php

namespace WPMVC\Commands\Traits;

use Exception;
use Ayuco\Exceptions\NoticeException;

/**
 * Trait used to set package namespace.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.8
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

            // Update Namespace in Main app file
            if (file_exists($this->rootPath . '/app/Main.php'))
            $this->replaceInFile( 
                'namespace ' . $currentnamespace,
                'namespace ' . $namespace,
                $this->rootPath.'/app/Main.php'
            );

            // Update Namespace in Model files
            if (is_dir($this->rootPath.'/app/Models')) 
                foreach (scandir($this->rootPath.'/app/Models') as $filename) {
                    $this->replaceInFile( 
                        'namespace ' . $currentnamespace,
                        'namespace ' . $namespace,
                        $this->rootPath.'/app/Models/' . $filename
                    );
                }

            // Update Namespace in Controller files
            if (is_dir($this->config['paths']['controllers'])) 
                foreach (scandir($this->config['paths']['controllers']) as $filename) {
                    $this->replaceInFile( 
                        'namespace ' . $currentnamespace,
                        'namespace ' . $namespace,
                        $this->config['paths']['controllers'] . $filename
                    );
                    $this->replaceInFile( 
                        'use ' . $currentnamespace,
                        'use ' . $namespace,
                        $this->config['paths']['controllers'] . $filename
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