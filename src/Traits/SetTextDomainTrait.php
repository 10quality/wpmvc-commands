<?php

namespace WPMVC\Commands\Traits;

use Exception;
use Ayuco\Exceptions\NoticeException;

/**
 * Trait used to set package text domain.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.17
 */
trait SetTextDomainTrait
{
    /**
     * Sets a projects text domain.
     * @since 1.1.0
     *
     * @param string $domain Text domain.
     */
    public function setTextDomain($domain)
    {
        try {
            $domain = preg_replace('/\s/', '-', strtolower(trim($domain)));
            $currentDomain = $this->config['localize']['textdomain'];
            // Replace in config file
            $this->replaceInFile('textdomain\\\'(|[\s]+)\=\>(|[\s]+)\\\''.$currentDomain.'\\\'', 'textdomain\' => \''.$domain.'\'', $this->configFilename);
            $this->config = include $this->configFilename;
            // Replace in package.json
            $packageJson = json_decode(file_get_contents($this->rootPath.'/package.json'));
            $this->replaceInFile(
                '"'.$packageJson->name.'"',
                '"'.$domain.'"',
                $this->rootPath . '/package.json'
            );
            // Replace in composer.json
            $composerJson = json_decode(file_get_contents($this->rootPath.'/composer.json'));
            $this->replaceInFile(
                $currentDomain.'"',
                $domain.'"',
                $this->rootPath . '/composer.json'
            );
            // Replace in project
            $filename = $this->config['type'] === 'theme'
                ? $this->rootPath.'/style.css'
                : $this->rootPath.'/plugin.php';
            $this->replaceInFile(
                'Text Domain: '.$currentDomain,
                'Text Domain: '.$domain,
                $filename
            );
            // Print end
            $this->_print_success('Text domain updated!');
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