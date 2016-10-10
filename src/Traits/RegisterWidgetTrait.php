<?php

namespace WPMVC\Commands\Traits;

use stdClass;
use Ayuco\Exceptions\NoticeException;

/**
 * Command that sets project name.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC
 * @version 1.0.0
 */
trait RegisterWidgetTrait
{
    /**
     * Registers and creates new widget.
     * @since 1.0.0
     *
     * @param string $name Widget class name.
     * @param array  $args Command arguments.
     */
    protected function registerWidget($name, $args)
    {
        $template = $this->getTemplate('widget.php');
        // Replace arguments
        $template = preg_replace(
            [
                '/\{0\}/',
                '/\{1\}/',
                '/\{2\}/',
            ],
            [
                $name,
                $this->config['type'] == 'theme' ? 'theme' : strtolower($this->config['namespace']),
                strtolower($this->config['namespace']),
            ],
            $template
        );
        // Check if folder exists.
        if (!is_dir($this->rootPath.'/app/Widgets'))
            mkdir($this->rootPath.'/app/Widgets');
        // Pull contents
        file_put_contents(
            $this->rootPath.'/app/Widgets/'.$name.'.php',
            $template
        );
        // Update composer.json autoload
        $json = json_decode(file_get_contents($this->rootPath.'/composer.json'));
        $jsonUpdated = false;
        if (!isset($json->autoload->{'psr-0'})) {
            $json->autoload->{'psr-0'} = ['' => []];
        }
        if (is_array($json->autoload->{'psr-0'})
            && !in_array('app\\Widgets', $json->autoload->{'psr-0'}[''])
        ) {
            $json->autoload->{'psr-0'}[''][] = 'app\\Widgets';
            $jsonUpdated = true;
        }
        if (is_array($json->autoload->{'psr-0'}->{_empty_})
            && !in_array('app\\Widgets', $json->autoload->{'psr-0'}->{_empty_})
        ) {
            $empty = $json->autoload->{'psr-0'}->{_empty_};
            unset($json->autoload->{'psr-0'}->{_empty_});
            $json->autoload->{'psr-0'} = ['' => $empty];
            $json->autoload->{'psr-0'}[''][] = 'app\\Widgets';
            $jsonUpdated = true;
        }
        if ($jsonUpdated) {
            file_put_contents(
                $this->rootPath.'/composer.json',
                $this->prettyJson(json_encode($json))
            );
        }
        // Dump autoload
        $this->_print('Widget registered!');
        $this->_lineBreak();
        exec( 'composer dump-autoload' );
    }
}