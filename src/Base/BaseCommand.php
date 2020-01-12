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
 * @package WPMVC\Commands
 * @version 1.1.2
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
        // Check for MVC configuration file
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
        if (substr($filename,-1) === '.') return;
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

    /**
     * Returns the contents of a template.
     * @since 1.0.1
     *
     * @param string $template Template name (i.e. style.css).
     *
     * @return string
     */
    protected function getTemplate($template)
    {
        $source = __DIR__.'/../../templates/'.$template;
        return file_exists($source) ? file_get_contents($source) : null;
    }

    /**
     * jsonpp - Pretty print JSON data
     *
     * In versions of PHP < 5.4.x, the json_encode() function does not yet provide a
     * pretty-print option. In lieu of forgoing the feature, an additional call can
     * be made to this function, passing in JSON text, and (optionally) a string to
     * be used for indentation.
     *
     * @param string $json  The JSON data, pre-encoded
     * @param string $istr  The indentation string
     *
     * @link https://github.com/ryanuber/projects/blob/master/PHP/JSON/jsonpp.php
     *
     * @return string
     */
    protected function prettyJson($json, $istr='  ')
    {
        $result = '';
        for($p=$q=$i=0; isset($json[$p]); $p++)
        {
            $json[$p] == '"' && ($p>0?$json[$p-1]:'') != '\\' && $q=!$q;
            if(!$q && strchr(" \t\n", $json[$p])){continue;}
            if(strchr('}]', $json[$p]) && !$q && $i--)
            {
                strchr('{[', $json[$p-1]) || $result .= "\n".str_repeat($istr, $i);
            }
            $result .= $json[$p];
            if(strchr(',{[', $json[$p]) && !$q)
            {
                $i += strchr('{[', $json[$p])===FALSE?0:1;
                strchr('}]', $json[$p+1]) || $result .= "\n".str_repeat($istr, $i);
            }
        }
        return $result;
    }
}
