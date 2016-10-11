<?php

namespace WPMVC\Commands;

use WPMVC\Commands\Traits\CreateViewTrait;
use WPMVC\Commands\Traits\CreateControllerTrait;
use WPMVC\Commands\Base\BaseCommand as Command;
use Ayuco\Exceptions\NoticeException;

/**
 * Command that creates stuff into wordpress mvc.
 * Creates views and controllers.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.0
 */
class CreateCommand extends Command
{
    use CreateViewTrait, CreateControllerTrait;

    /**
     * Command key.
     * @since 1.0.0
     * @var string
     */
    protected $key = 'create';

    /**
     * Command description.
     * @since 1.0.0
     * @var string
     */
    protected $description = 'Creates controllers and views. i.e. ayuco create view:posts.metabox';

    /**
     * Calls to command action.
     * @since 1.0.0
     *
     * @param array $args Action arguments.
     */
    public function call($args = [])
    {
        if (count($args) == 0 || empty($args[2]))
            throw new NoticeException('Command "'.$this->key.'": Expecting an object to create (view|controller).');

        $object = explode(':', $args[2]);

        switch ($object[0]) {
            case 'view':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": View key name is missing.');
                $this->createView($object[1], $args);
                break;
            case 'controller':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Controller definition is missing.');
                $controller = explode('@', $object[1]);
                $this->createController($controller[0], $args);
                // Create method
                if (count($controller) > 1)
                    $this->createControllerMethod($controller[0], $controller[1]);
                break;
        }
    }
}