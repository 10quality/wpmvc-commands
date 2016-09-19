<?php

namespace WPMVC\Commands\Base;

use Ayuco\Command;
use Ayuco\Exceptions\NoticeException;

/**
 * Base command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC
 * @version 1.0.0
 */
class BaseCommand extends Command
{
    /**
     * Apps config file.
     * @since 1.0.0
     * @var string
     */
    protected $config;

    /**
     * Apps config filename.
     * @since 1.0.0
     * @var string
     */
    protected $configFilename;

    /**
     * Projects root path.
     * @since 1.0.0
     * @var string
     */
    protected $rootPath;

    /**
     * Default controller.
     * Loads app configuration.
     * @since 1.0.0
     *     
     * @param string $rootPath Projects root path
     */
    public function __construct($rootPath)
    {
        $this->rootPath = $rootPath;
        // Checkfor MVC configuration file
        $this->configFilename = file_exists($this->rootPath . '/app/config/app.php')
            ? $this->rootPath . '/app/config/app.php'
            : null;
         if (empty($this->configFilename))
            throw new NoticeException($this->key.'Command: No configuration file found.');
        $this->config = include $this->configFilename;
    }

    /**
     * Replaces needle in file.
     * @since 1.0.0
     *
     * @param string $needle  Needle to replace with.
     * @param string $replace What to replace with.
     * @param string $filename
     */
    protected function replaceInFile($needle, $replace, $filename)
    {
        if ($filename == '.' || $filename == '..') return;
        file_put_contents( 
            $filename, 
            preg_replace(
                '/' . $needle . '/',
                $replace,
                file_get_contents($filename)
            ) 
        );
    }

    /**
     * Copies template file to specified destination.
     * @since 1.0.0
     *
     * @param string $template Template name (i.e. style.css).
     * @param string $dest     Destination path and filename.
     */
    protected function copyTemplate($template, $dest)
    {
        $source = __DIR__.'/../../templates/'.$template;
        if (file_exists($source)) {
            return file_put_contents($dest, file_get_contents($source));
        }
    }
}