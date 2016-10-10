<?php

namespace WPMVC\Commands;

use WPMVC\Commands\Base\BaseCommand as Command;
use Ayuco\Exceptions\NoticeException;

/**
 * Command that sets project name.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.1
 */
class SetNameCommand extends Command
{
    /**
     * Command key.
     * @since 1.0.0
     * @var string
     */
    protected $key = 'setname';

    /**
     * Command description.
     * @since 1.0.0
     * @var string
     */
    protected $description = 'Changes project\'s namespace. Expects name as parameter.';

    /**
     * Calls to command action.
     * @since 1.0.0
     *
     * @param array $args Action arguments.
     */
    public function call($args = [])
    {
        if (count($args) == 0 || empty($args[2]))
            throw new NoticeException('Command "'.$this->key.'": Expecting a name.');

        $this->setName($args[2]);
    }

    /**
     * Sets project name.
     * @since 1.0.0
     * @since 1.0.1 Added strtolower.
     *
     * @param string $name Project name.
     */
    public function setName($name)
    {
        if (empty($name))
            throw new NoticeException('Command "'.$this->key.'": No name given.');

        // Checkfor MVC configuration file
        if (empty($this->configFilename))
            throw new NoticeException('Command "'.$this->key.'": No configuration file found.');
        $currentname = $this->config['namespace'];

        $this->replaceInFile($currentname, $name, $this->configFilename);
        if (file_exists($this->rootPath . '/app/Main.php'))
            $this->replaceInFile( 
                'namespace ' . $currentname,
                'namespace ' . $name
                , $this->rootPath.'/app/Main.php'
            );

        foreach (scandir($this->rootPath.'/app/Models') as $filename) {
            $this->replaceInFile( 
                'namespace ' . $currentname,
                'namespace ' . $name,
                $this->rootPath.'/app/Models/' . $filename
            );
        }

        foreach (scandir($config['paths']['controllers']) as $filename) {
            $this->replaceInFile( 
                'namespace ' . $currentname,
                'namespace ' . $name,
                $config['paths']['controllers'] . $filename
            );
            $this->replaceInFile( 
                'use ' . $currentname,
                'use ' . $name,
                $config['paths']['controllers'] . $filename
            );
        }

        if (file_exists($this->rootPath . '/composer.json'))
            $this->replaceInFile( 
                '"' . $currentname,
                '"' . $name,
                $this->rootPath . '/composer.json'
            );

        if (file_exists($this->rootPath . '/package.json'))
            $this->replaceInFile( 
                '"' . strtolower($currentname),
                '"' . strtolower($name),
                $this->rootPath . '/package.json'
            );

        $this->_print('Namespace changed!');
        $this->_lineBreak();

        if (file_exists($this->rootPath . '/composer.json'))
            exec( 'composer dump-autoload' );
    }
}