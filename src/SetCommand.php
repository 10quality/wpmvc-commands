<?php

namespace WPMVC\Commands;

use WPMVC\Commands\Traits\SetVersionTrait;
use WPMVC\Commands\Base\BaseCommand as Command;
use Ayuco\Exceptions\NoticeException;

/**
 * Command that sets configuration and other values in wordpress mvc.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.4
 */
class SetCommand extends Command
{
    use SetVersionTrait;

    /**
     * Command key.
     * @since 1.0.4
     * @var string
     */
    protected $key = 'set';

    /**
     * Command description.
     * @since 1.0.4
     * @var string
     */
    protected $description = 'Sets configuration and values in Wordpress MVC.';

    /**
     * Calls to command action.
     * @since 1.0.4
     *
     * @param array $args Action arguments.
     */
    public function call($args = [])
    {
        if (count($args) == 0 || empty($args[2]))
            throw new NoticeException('Command "'.$this->key.'": Expecting a setting (version).');

        $object = explode(':', $args[2]);

        // Validations
        if (!in_array($object[0], ['version']))
            throw new NoticeException('Command "'.$this->key.'": Invalid setting. Expecting version.');

        switch ($object[0]) {
            case 'version':
                // Validate second parameter
                if ($object[0] === 'version' && !isset($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Expecting a version.');
                $this->setVersion($object[1]);
                break;
        }
    }
}