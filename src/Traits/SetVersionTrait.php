<?php

namespace WPMVC\Commands\Traits;

use Exception;
use Ayuco\Exceptions\NoticeException;

/**
 * Trait used to set package versions.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.4
 */
trait SetVersionTrait
{
    /**
     * Sets a package version.
     * @since 1.0.4
     *
     * @param string $version Package version (i.e. 1.0.0).
     */
    protected function setVersion($version)
    {
        try {
            // Prepare
            $currentVersion = $this->config['version'];
            // Replace in config file
            $this->replaceInFile($currentVersion, $version, $this->configFilename);
            // Replace in package.json
            $packageJson = json_decode(file_get_contents($this->rootPath.'/package.json'));
            $this->replaceInFile(
                '"version": "'.$packageJson->version.'"',
                '"version": "'.$version.'"',
                $this->rootPath.'/package.json'
            );
            // Replace in project
            $filename = $this->config['type'] === 'theme'
                ? $this->rootPath.'/style.css'
                : $this->rootPath.'/plugin.php';
            preg_match(
                '/[Vv]ersion\:[|\s][0-9\.vV]+/',
                file_get_contents($filename),
                $matches
            );
            if (count($matches) > 0)
                $this->replaceInFile(
                    $matches[0],
                    'Version: '.$version,
                    $filename
                );
            // Print end
            $this->_print('Version updated!');
            $this->_lineBreak();
        } catch (Exception $e) {
            file_put_contents(
                $this->rootPath.'/error_log',
                $e->getMessage()
            );
            throw new NoticeException('Command "'.$this->key.'": Fatal error occurred.');
        }
    }
}