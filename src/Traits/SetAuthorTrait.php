<?php

namespace WPMVC\Commands\Traits;

use Exception;
use Ayuco\Exceptions\NoticeException;

/**
 * Trait used to set the project's autho.
 *
 * @author Ale Mostajo <http://about.me/amostajo>
 * @copyright 10 Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.6
 */
trait SetAuthorTrait
{
    /**
     * Sets a projects text domain.
     * @since 1.1.0
     */
    public function setAuthor()
    {
        try {
            $input = ['name' => null, 'contact' => null];
            // Ask for author information
            $this->_lineBreak();
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('Enter the author\'s name:');
            $this->_lineBreak();
            $input['name'] = $this->listener->getInput();
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('Enter the author\'s contact info (for example an email or URL):');
            $this->_lineBreak();
            $input['contact'] = $this->listener->getInput();
            $this->_lineBreak();
            // Prepare authro
            $author = empty($input['name']) ? null : $input['name'];
            if ($author && !empty($input['contact']))
                $author .= ' <'.$input['contact'].'>';
            if (array_key_exists('author', $this->config)) {
                $current = $this->config['author'];
                // Replace in config file
                $this->replaceInFile('author\'(|\s)=>(|\s)\''.$current.'\'', 'author\' => \''.$author.'\'', $this->configFilename);
                $this->config = include $this->configFilename;
                // Print end
                $this->_print('Author updated!');
                $this->_lineBreak();
            } else {
                throw new NoticeException('Author key missing at "app/Config/app.php". Add the "author" setting in the configuration file to use this command.');
            }
        } catch (Exception $e) {
            file_put_contents(
                $this->rootPath.'/error_log',
                $e->getMessage()
            );
            throw new NoticeException('Command "'.$this->key.'": Fatal error occurred.');
        }
    }
}