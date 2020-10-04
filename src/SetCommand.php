<?php

namespace WPMVC\Commands;

use WPMVC\Commands\Traits\SetNamespaceTrait;
use WPMVC\Commands\Traits\SetVersionTrait;
use WPMVC\Commands\Traits\SetTextDomainTrait;
use WPMVC\Commands\Traits\SetAuthorTrait;
use WPMVC\Commands\Traits\SetLicenseTrait;
use WPMVC\Commands\Base\BaseCommand as Command;
use Ayuco\Exceptions\NoticeException;

/**
 * Command that sets configuration and other values in WordPress MVC.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.12
 */
class SetCommand extends Command
{
    use SetNamespaceTrait, SetVersionTrait, SetTextDomainTrait, SetAuthorTrait, SetLicenseTrait;

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
    protected $description = 'Sets configuration and values in WordPress MVC. Supported settings are namespace|version|domain|author|license. (e.g. ayuco set version:1.0.0).';

    /**
     * Calls to command action.
     * @since 1.0.4
     *
     * @param array $args Action arguments.
     */
    public function call($args = [])
    {
        // Check for MVC configuration file
        if (empty($this->configFilename))
            throw new NoticeException('Command "'.$this->key.'": No configuration file found.');
        
        if (count($args) == 0 || empty($args[2]))
            throw new NoticeException('Command "'.$this->key.'": Expecting a setting (namespace|version|domain|author|license).');

        $object = explode(':', $args[2]);

        // Validations
        if (!in_array($object[0], ['namespace','version','domain','author','license']))
            throw new NoticeException('Command "'.$this->key.'": Invalid setting. Expecting (namespace|version|domain|author|license).');

        switch ($object[0]) {
            case 'namespace':
                // Validate second parameter
                if ($object[0] === 'namespace' && empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Expecting a namespace.');
                $this->setNamespace($object[1]);
                break;
            case 'version':
                // Validate second parameter
                if ($object[0] === 'version' && empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Expecting a version.');
                $this->setVersion($object[1]);
                break;
            case 'domain':
                // Validate second parameter
                if ($object[0] === 'domain' && empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Expecting a text domain.');
                $this->setTextDomain($object[1]);
                break;
            case 'author':
                // Calls wizard
                $this->setAuthor();
                break;
            case 'license':
                // Calls wizard
                $this->setLicense();
                break;
        }
    }
}
