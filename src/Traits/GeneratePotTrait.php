<?php

namespace WPMVC\Commands\Traits;

use Exception;
use Ayuco\Exceptions\NoticeException;
use Gettext\Translations;
use Gettext\Generator\MoGenerator;
use Gettext\Generator\PoGenerator;
use Gettext\Loader\PoLoader;
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
            $filename = $this->config['localize']['path'].$domain.'.pot';
            $is_update = file_exists($filename);
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
            $output = $generator->generateString($translations, $filename);
            $output = str_replace($this->rootPath, '', $output);
            $output = str_replace('#: \assets', '#: /assets', $output);
            file_put_contents($filename, $output);
            // Print end
            $this->_print($is_update ? 'POT file updated!' : 'POT file generated!');
            $this->_lineBreak();
        } catch (Exception $e) {
            error_log($e);
            throw new NoticeException('Command "'.$this->key.'": Fatal error ocurred.');
        }
    }

    /**
     * Generate PO file.
     * @since 1.1.0
     * 
     * @param string $locale PO locale.
     * @param string $lang   Pot default language.
     */
    protected function generatePo($locale, $lang = 'en')
    {
        try {
            $domain = $this->config['localize']['textdomain'];
            // Do we have a POT file?
            $pot_filename = $this->config['localize']['path'].$domain.'.pot';
            $this->generatePot($lang);
            // Does PO file already exist?
            $po_filename = $this->config['localize']['path'].$domain.'-'.$locale.'.po';
            $translations = null;
            $to_update = false;
            $loader = new PoLoader;
            if (file_exists($po_filename)) {
                $translations = $loader->loadFile($po_filename);
                $translations = $translations->mergeWith($loader->loadFile($pot_filename));
                $to_update = true;
                $translations->getHeaders()->set('PO-Update-Date', date('Y-m-d H:i:s'));
            } else {
                $translations = $loader->loadFile($pot_filename);
                $translations->getHeaders()->set('PO-Creation-Date', date('Y-m-d H:i:s'));
            }
            $translations->getHeaders()->setLanguage($locale);
            // Prepare
            if (!is_dir($this->config['localize']['path']))
                mkdir($this->config['localize']['path'], 0777, true);
            // Write pot file
            $generator = new PoGenerator();
            $generator->generateFile($translations, $po_filename);
            // Print end
            $this->_print('PO:'.$locale.($to_update ? ' file updated!' : ' file generated!'));
            $this->_lineBreak();
        } catch (Exception $e) {
            error_log($e);
            throw new NoticeException('Command "'.$this->key.'": Fatal error ocurred.');
        }
    }

    /**
     * Generate MO file.
     * @since 1.1.0
     * 
     * @param string $locale PO locale.
     */
    protected function generateMo($locale)
    {
        try {
            $domain = $this->config['localize']['textdomain'];
            // Filenames
            $po_filename = $this->config['localize']['path'].$domain.'-'.$locale.'.po';
            // Handle PO
            if (!file_exists($po_filename))
                throw new NoticeException('PO:'.$locale.' file doesn\'t exists, nothing to generate.');
            $loader = new PoLoader;
            $translations = $loader->loadFile($po_filename);
            // Write pot file
            $generator = new MoGenerator();
            $generator->generateFile($translations, $this->config['localize']['path'].$domain.'-'.$locale.'.mo');
            // Print end
            $this->_print('MO:'.$locale.' file generated!');
            $this->_lineBreak();
        } catch (Exception $e) {
            error_log($e);
            throw new NoticeException('Command "'.$this->key.'": Fatal error ocurred.');
        }
    }
}