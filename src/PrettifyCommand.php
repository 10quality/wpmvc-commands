<?php

namespace WPMVC\Commands;

use Exception;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use WPMVC\Commands\Base\BaseCommand as Command;
use Ayuco\Exceptions\NoticeException;
use WPMVC\Commands\Core\Builder;

/**
 * Prettifies PHP content using this package's pretty printer.
 *
 * @author Ale Mostajo
 * @copyright 10 Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.10
 */
class PrettifyCommand extends Command
{
    /**
     * Command key.
     * @since 1.1.9
     * @var string
     */
    protected $key = 'prettify';

    /**
     * Command description.
     * @since 1.1.9
     * @var string
     */
    protected $description = 'Prettifies the PHP code inside the "/app" directory.';

    /**
     * Calls to command action.
     * @since 1.1.9
     *
     * @param array $args Action arguments.
     */
    public function call($args = [])
    {
        try {
            $path = $this->rootPath.'/app';
            if (!is_dir($path)) return;
            $dir = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
            foreach (new RecursiveIteratorIterator($dir, RecursiveIteratorIterator::SELF_FIRST) as $filename => $item) {
                if (!$item->isDir()
                    && !preg_match('/app(\/|\\\)(Boot|Config)(\/|\\\)/', $filename)
                    && $item->getExtension() === 'php'
                ) {
                    $builder = Builder::parser($filename, array_key_exists('nopretty', $this->options));
                    $builder->build();
                }
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
