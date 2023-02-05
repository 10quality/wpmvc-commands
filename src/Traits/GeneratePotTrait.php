<?php

namespace WPMVC\Commands\Traits;

use Exception;
use Ayuco\Exceptions\NoticeException;
use Gettext\Translations;
use Gettext\Generator\PoGenerator;
use TenQuality\Gettext\Scanner\WPJsScanner;
use TenQuality\Gettext\Scanner\WPPhpScanner;

/**
 * Generates POT file.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.17
 */
trait GeneratePotTrait
{
    /**
     * Generate pot file.
     * @since 1.1.0
     * 
     * @param string $lang Pot default language.
     */
    protected function generatePot($lang = 'en')
    {
        try {
            $domain = $this->config['localize']['textdomain'];
            $translations = Translations::create($domain, $lang);
            // Prepare headers
            $translations->getHeaders()->set('Project-Id-Version', $this->config['namespace']);
            $translations->getHeaders()->set('POT-Creation-Date', date('Y-m-d H:i:s'));
            $translations->getHeaders()->set('MIME-Version', $this->config['version']);
            $translations->getHeaders()->set('Content-Type', 'text/plain; charset=UTF-8');
            $translations->getHeaders()->set('Last-Translator', $this->config['author']);
            $translations->getHeaders()->set('X-Generator', 'WordPress MVC Commands 1.1');
            // Php files
            $scanner = new WPPhpScanner(
                Translations::create($domain)
            );
            foreach (glob($this->rootPath.'*.php') as $file) {
                $scanner->scanFile($file);
            }
            foreach (glob($this->getAppPath().'*.php') as $file) {
                $scanner->scanFile($file);
            }
            foreach (glob($this->getAppPath().'**/*.php') as $file) {
                $scanner->scanFile($file);
            }
            foreach (glob($this->getViewsPath().'*.php') as $file) {
                $scanner->scanFile($file);
            }
            foreach (glob($this->getViewsPath().'**/*.php') as $file) {
                $scanner->scanFile($file);
            }
            $scannedTranslations = $scanner->getTranslations();
            if (array_key_exists($domain, $scannedTranslations))
                $translations = $translations->mergeWith($scannedTranslations[$domain]);
            // Js files
            $scanner = new WPJsScanner(
                Translations::create($domain)
            );
            foreach (glob($this->getAssetsPath().'js/*.js') as $file) {
                $scanner->scanFile($file);
            }
            foreach (glob($this->getAssetsPath().'js/**/*.js') as $file) {
                $scanner->scanFile($file);
            }
            $scannedTranslations = $scanner->getTranslations();
            if (array_key_exists($domain, $scannedTranslations))
                $translations = $translations->mergeWith($scannedTranslations[$domain]);
            // Prepare
            if (!is_dir($this->config['localize']['path']))
                mkdir($this->config['localize']['path'], 0777, true);
            // Write pot file
            $generator = new PoGenerator();
            $generator->generateFile($translations, $this->config['localize']['path'].$domain.'.pot');
            // Print end
            $this->_print('POT file generated!');
            $this->_lineBreak();
        } catch (Exception $e) {
            error_log($e);
            throw new NoticeException('Command "'.$this->key.'": Fatal error ocurred.');
        }
    }
}