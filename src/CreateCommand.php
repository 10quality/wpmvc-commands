<?php

namespace WPMVC\Commands;

use WPMVC\Commands\Traits\CreateViewTrait;
use WPMVC\Commands\Traits\CreateModelTrait;
use WPMVC\Commands\Traits\CreateControllerTrait;
use WPMVC\Commands\Base\BaseCommand as Command;
use Ayuco\Exceptions\NoticeException;

/**
 * Command that creates stuff into WordPress MVC.
 * Creates views and controllers.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.3
 */
class CreateCommand extends Command
{
    use CreateModelTrait, CreateViewTrait, CreateControllerTrait;

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
    protected $description = 'Creates models, views and controllers. i.e. ayuco create view:posts.metabox';

    /**
     * Calls to command action.
     * @since 1.0.0
     * @since 1.0.1 Added type definition.
     * @since 1.0.2 Added option model.
     * @since 1.0.3 Fixed category option model creation.
     *
     * @param array $args Action arguments.
     */
    public function call($args = [])
    {
        if (count($args) == 0 || empty($args[2]))
            throw new NoticeException('Command "'.$this->key.'": Expecting an object to create (view|controller|model|postmodel|optionmodel|usermodel|categorymodel|termmodel).');

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
            case 'model':
            case 'postmodel':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Model definition is missing.');
                $this->createModel($object[1]);
                // Add type
                if (isset($args[3]))
                    $this->createModelProperty($object[1], 'type', $args[3]);
                break;
            case 'optionmodel':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Model definition is missing.');
                $this->createModel($object[1], 'OptionModel');
                // Add id
                $this->createModelProperty($object[1], 'id', isset($args[3]) ? $args[3] : uniqid());
                break;
            case 'usermodel':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Model definition is missing.');
                $this->createModel($object[1], 'UserModel');
                break;
            case 'categorymodel':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Model definition is missing.');
                $this->createModel($object[1], 'CategoryModel');
                break;
            case 'termmodel':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Model definition is missing.');
                $this->createModel($object[1], 'TermModel', 'FindTermTrait');
                // Add taxonomy
                if (isset($args[3]))
                    $this->createModelProperty($object[1], 'model_taxonomy', $args[3]);
                break;
        }
    }
}
