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
 * @version 1.1.8
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
    protected $description = 'DEPRECATED. The "setname" command will no longer be maintained; use the "set" command instead.';

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

        $command = $this->listener->get('set');
        if (!$command)
            throw new NoticeException('SetNameCommand: "set" command is not registered in ayuco.');

        $namespace = empty($args[2]) ? 'MyApp' : str_replace(' ', '', ucwords($args[2]));
        $command->setNamespace($namespace);
    }

    /**
     * Sets project name.
     * @since 1.0.0
     *
     * @param string $name Project name.
     */
    public function setName($name)
    {
        if (empty($name))
            throw new NoticeException('Command "'.$this->key.'": No name given.');

        $command = $this->listener->get('set');
        if (!$command)
            throw new NoticeException('SetNameCommand: "set" command is not registered in ayuco.');

        $namespace = empty($name) ? 'MyApp' : str_replace(' ', '', ucwords($name));
        $command->setNamespace($namespace);
    }
}
