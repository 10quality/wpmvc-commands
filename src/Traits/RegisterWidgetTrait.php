<?php

namespace WPMVC\Commands\Traits;

use stdClass;
use Ayuco\Exceptions\NoticeException;
use WPMVC\Commands\Core\Builder;
use WPMVC\Commands\Visitors\AddMethodCallVisitor;

/**
 * Trait used to register widgets in a command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.6
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
        try {
            // Check if folder exists.
            if (!is_dir($this->rootPath.'/app/Widgets'))
                mkdir($this->rootPath.'/app/Widgets');
            // Pull contents
            $filename = $this->rootPath.'/app/Widgets/'.$name.'.php';
            if (!is_file($filename)) {
                // Get contents
                $template = $this->getTemplate('widget.php');
                // Replace arguments
                $template = preg_replace(
                    [
                        '/\{0\}/',
                        '/\{1\}/',
                        '/\{2\}/',
                        '/\{3\}/',
                        '/\{4\}/',
                    ],
                    [
                        $name,
                        $this->config['type'] == 'theme' ? 'theme' : $this->config['namespace'],
                        array_key_exists('author', $this->config) ? $this->config['author'] : '',
                        $this->config['localize']['textdomain'],
                        $this->config['version'],
                    ],
                    $template
                );
                // Save contents in file
                file_put_contents($filename, $template);
                // Update composer.json autoload
                $json = json_decode(file_get_contents($this->rootPath.'/composer.json'));
                $jsonUpdated = false;
                if (!isset($json->autoload->{'psr-0'})) {
                    $json->autoload->{'psr-0'} = new stdClass;
                    $json->autoload->{'psr-0'}->{''} = [];
                }
                if (is_array($json->autoload->{'psr-0'})
                    && !in_array('app\\Widgets', $json->autoload->{'psr-0'}[''])
                ) {
                    $json->autoload->{'psr-0'}[''][] = 'app\\Widgets';
                    $jsonUpdated = true;
                }
                if (isset($json->autoload->{'psr-0'})
                    && is_object($json->autoload->{'psr-0'})
                    && is_array($json->autoload->{'psr-0'}->{''})
                    && !in_array('app\\Widgets', $json->autoload->{'psr-0'}->{''})
                ) {
                    $empty = $json->autoload->{'psr-0'}->{''};
                    unset($json->autoload->{'psr-0'}->{''});
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
                // Add to main
                $builder = Builder::parser($this->rootPath.'/app/Main.php');
                $builder->addVisitor(new AddMethodCallVisitor($this->config, 'init', 'add_widget', [$name]));
                $builder->build();
                // Dump autoload
                $this->_print('Widget registered!');
                $this->_lineBreak();
                exec( 'composer dump-autoload --no-plugins' );
            } else {
                // Dump autoload
                $this->_print('Widget exists!');
                $this->_lineBreak();
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