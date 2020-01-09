<?php

namespace WPMVC\Commands;

use WPMVC\Commands\Traits\SetVersionTrait;
use WPMVC\Commands\Traits\SetTextDomainTrait;
use WPMVC\Commands\Base\BaseCommand as Command;
use Ayuco\Exceptions\NoticeException;

/**
 * Command that sets configuration and other values in WordPress MVC.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.0
 */
class SetCommand extends Command
{
    use SetVersionTrait, SetTextDomainTrait;

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
    protected $description = 'Sets configuration and values in WordPress MVC.';

    /**
     * Calls to command action.
     * @since 1.0.4
     * @since 1.1.0 Support for text domains.
     *
     * @param array $args Action arguments.
     */
    public function call($args = [])
    {
        if (count($args) == 0 || empty($args[2]))
            throw new NoticeException('Command "'.$this->key.'": Expecting a setting (version|domain).');

        $object = explode(':', $args[2]);

        // Validations
        if (!in_array($object[0], ['version','domain']))
            throw new NoticeException('Command "'.$this->key.'": Invalid setting. Expecting (version|domain).');

        switch ($object[0]) {
            case 'version':
                // Validate second parameter
                if ($object[0] === 'version' && !isset($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Expecting a version.');
                $this->setVersion($object[1]);
                break;
            case 'domain':
                // Validate second parameter
                if ($object[0] === 'domain' && !isset($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Expecting a text domain.');
                $this->setTextDomain($object[1]);
                break;
        }
    }
}