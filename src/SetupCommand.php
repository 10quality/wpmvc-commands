<?php

namespace WPMVC\Commands;

use WPMVC\Commands\Base\BaseCommand as Command;
use Ayuco\Exceptions\NoticeException;

/**
 * Setup command.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.1
 */
class SetupCommand extends Command
{
    /**
     * Command key.
     * @since 1.0.0
     * @var string
     */
    protected $key = 'setup';

    /**
     * Command description.
     * @since 1.0.0
     * @var string
     */
    protected $description = 'Wordpress MVC setup wizard.';

    /**
     * Calls to command action.
     * @since 1.0.0
     * @since 1.0.1 Removed strtolower
     *
     * @param array $args Action arguments.
     */
    public function call($args = [])
    {
        $command = $this->listener->get('setname');
        if (!$command)
            throw new NoticeException('SetupCommand: "setname" command is not registered in ayuco.');
            
        try {
            $this->_lineBreak();
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('Wordpress MVC (AYUCO) Setup');
            $this->_lineBreak();
            // NAMESPACE || PROJECT NAME
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('Enter your project\'s namespace (example: MyProject):');
            $this->_lineBreak();
            $namespace = $this->listener->getInput();
            $command->setName(empty($namespace)
                ? 'MyApp'
                : str_replace(' ', '', ucwords($namespace))
            );
            // TYPE
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('Enter your project\'s type (options: "theme" or "plugin"):');
            $this->_lineBreak();
            $type = $this->listener->getInput();
            $this->setType($type);
            // DESCRIPTION
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->getDescription();
            $this->_lineBreak();
            // End
            $this->_print('------------------------------');
            $this->_lineBreak();
            $this->_print('Your plugin namespace is "%s"', $namespace);
            $this->_lineBreak();
            $this->_print('Setup completed!');
            $this->_lineBreak();
            $this->_print('------------------------------');
            $this->_lineBreak();
        } catch (NoticeException $e) {
            throw new NoticeException('Command "setup": Failed! ' . $e->getMessage());
        }
    }

    /**
     * Sets project type.
     * @since 1.0.0
     *
     * @param string $type Type.
     */
    private function setType($type = '')
    {
        $type = preg_replace('/\"/', '', mb_strtolower($type));
        if ($type !== 'p' && $type !== 'plugin' && $type !== 't' && $type !== 'theme')
            throw new NoticeException('Type is wrong. Must be either "theme" or "plugin".');

        if ($type === 'p')
            $type = 'plugin';
        if ($type === 't')
            $type = 'theme';

        // Type handling
        $currentType = $this->config['type'];
        $this->replaceInFile('\''.$currentType.'\'', '\''.$type.'\'', $this->configFilename);

        $this->_print('Type set!');
        $this->_lineBreak();

        // Basic file structure
        switch ($type) {
            case 'plugin':
                $this->copyTemplate('plugin.php', $this->rootPath.'/plugin.php');
                break;
            case 'theme':
                $this->copyTemplate('functions.php', $this->rootPath.'/functions.php');
                $this->copyTemplate('index.php', $this->rootPath.'/index.php');
                $this->copyTemplate('style.css', $this->rootPath.'/style.css');
                break;
        }

        $this->_print('Project\'s base file(s) generated!');
        $this->_lineBreak();
    }
}