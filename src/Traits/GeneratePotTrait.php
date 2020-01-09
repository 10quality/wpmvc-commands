<?php

namespace WPMVC\Commands\Traits;

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Ayuco\Exceptions\NoticeException;

/**
 * Generates POT file.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.0
 */
trait GeneratePotTrait
{
    /**
     * Generate pot file.
     * @since 1.1.0
     * 
     * @param string $textdomain Domain name.
     * @param string $lang       Pot default language.
     */
    protected function generatePot($textdomain = null, $lang = 'en')
    {
        try {
            // Search project
            if (empty($textdomain))
                $textdomain = $this->config['localize']['textdomain'];
            $pot = $this->getPotHeader($lang, $textdomain);
            $lang_regex = '/_[_enx]\([|\s][\\\'\"][\s\S]+?(?=[\\\'|\"]'.$textdomain.'[\\\'|\"])/';
            $string_regex = '/[\\\'|\"]([^\\\'|\"]+)[\\\'|\"]/';
            // App folder
            $dir = new RecursiveDirectoryIterator($this->config['paths']['base'], RecursiveDirectoryIterator::SKIP_DOTS);
            foreach (new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST) as $filename => $item) {
                if($item->isDir())
                    continue;
                $content = file_get_contents($filename);
                preg_match_all($lang_regex, $content, $matches );
                if (empty($matches))
                    continue;
                foreach ($matches[0] as $match) {
                    $this->evalPotMatch($pot, $match, $string_regex);
                }
            }
            // Views folder
            $dir = new RecursiveDirectoryIterator($this->config['paths']['views'], RecursiveDirectoryIterator::SKIP_DOTS);
            foreach (new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST) as $filename => $item) {
                if($item->isDir())
                    continue;
                $content = file_get_contents($filename);
                preg_match_all($lang_regex, $content, $matches );
                if (empty($matches))
                    continue;
                foreach ($matches[0] as $match) {
                    $this->evalPotMatch($pot, $match, $string_regex);
                }
            }
            // Write pot file
            $filename = $this->config['localize']['path'].$textdomain.'.pot';
            if (!is_dir($this->config['localize']['path']))
                mkdir($this->config['localize']['path']);
            if (file_exists($filename))
                unlink($filename);
            file_put_contents($filename,implode("\n", $pot));
            // Print end
            $this->_print('POT file generated!');
            $this->_lineBreak();
        } catch (Exception $e) {
            error_log($e);
            throw new NoticeException('Command "'.$this->key.'": Fatal error ocurred.');
        }
    }
    /**
     * Returns POT basic header.
     * @since 1.1.0
     * 
     * @param string $lang       Pot default language.
     * @param string $textdomain
     * 
     * @return array
     */
    private function getPotHeader($lang, $textdomain)
    {
        return [
            '# Copyright (C) '.date('Y').' 10 Quality <info@10quality.com>',
            'msgid ""',
            'msgstr ""',
            '"Project-Id-Version: '.$textdomain.'\n"',
            '"POT-Creation-Date: '.date('Y-m-d H:i:s').'\n"',
            '"PO-Creation-Date: '.date('Y-m-d H:i:s').'\n"',
            '"MIME-Version: 1.0\n"',
            '"Content-Type: text/plain; charset=UTF-8\n"',
            '"Content-Transfer-Encoding: 8bit\n"',
            '"X-Generator: WordPress MVC Commands 1.1.0\n"',
            '"Language: '.$lang.'\n"',
        ];
    }
    /**
     * Appends a new text translation line to a po file.
     * @since 1.1.0
     * 
     * @param array  &$po         Po or Pot file.
     * @param string $text        Text to append.
     * @param string $translation Text translation append.
     */
    private function appendPotText(&$po, $text, $translation = '')
    {
        if (!in_array($text, $po)) {
            $po[] = '';
            $po[] = 'msgid "'.$text.'"';
            $po[] = 'msgstr "'.$translation.'"';
        }
    }
    /**
     * Evaluates match and appends string to Po/Pot file.
     * @since 1.1.0
     * 
     * @param array  &$po          Po or Pot file.
     * @param string $match        String match.
     * @param string $string_regex String regex rule.
     */
    private function evalPotMatch(&$po, &$match, &$string_regex)
    {
        if (strpos($match, '_n') !== false) {
            preg_match_all($string_regex, $match, $strings );
            for ($i = 0; $i < count($strings[1]); $i++) {
                $this->appendPotText($po, $this->parsePotString($strings[1][$i]));
            }
        } else {
            preg_match($string_regex, $match, $strings );
            $this->appendPotText($po, $this->parsePotString($strings[1]));
        }
    }
    /**
     * Returns parsed pot string.
     * @since 1.1.0
     * 
     * @param string $string
     * 
     * @return string
     */
    private function parsePotString($string)
    {
        return str_replace('"', '\"', $string);
    }
}