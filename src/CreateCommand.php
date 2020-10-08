<?php

namespace WPMVC\Commands;

use WPMVC\Commands\Traits\CreateViewTrait;
use WPMVC\Commands\Traits\CreateModelTrait;
use WPMVC\Commands\Traits\CreateControllerTrait;
use WPMVC\Commands\Traits\CreateAssetTrait;
use WPMVC\Commands\Traits\UpdateCommentTrait;
use WPMVC\Commands\Traits\CreateTestTrait;
use WPMVC\Commands\Traits\CreateBlockTrait;
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
 * @version 1.2.0
 */
class CreateCommand extends Command
{
    use CreateModelTrait, CreateViewTrait, CreateControllerTrait, CreateAssetTrait, UpdateCommentTrait, CreateTestTrait, CreateBlockTrait;

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
    protected $description = 'Creates models, views, controllers and assets. Supported object types are view|controller|model|postmodel|optionmodel|usermodel|termmodel|commentmodel|js|css|sass|scss|block|test. (e.g. ayuco create view:posts.metabox).';

    /**
     * Calls to command action.
     * @since 1.0.0
     *
     * @param array $args Action arguments.
     */
    public function call($args = [])
    {
        if (count($args) == 0 || empty($args[2]))
            throw new NoticeException('Command "'.$this->key.'": Expecting an object to create (view|controller|model|postmodel|optionmodel|usermodel|termmodel|commentmodel|js|css|sass|scss|block|test).');

        $object = explode(':', $args[2]);

        switch ($object[0]) {
            case 'view':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": View key name is missing.');
                $this->createView($object[1], isset($args[3]) ? $args[3] : 'view.php');
                break;
            case 'controller':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Controller definition is missing.');
                $controller = explode('@', $object[1]);
                $this->createController($controller[0], $args);
                // Create methods
                if (count($controller) > 1)
                    foreach (array_slice($controller, 1) as $controller_method)
                        $this->createControllerMethod($controller[0], $controller_method);
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
            case 'termmodel':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Model definition is missing.');
                $this->createModel($object[1], 'TermModel', 'FindTermTrait');
                // Add taxonomy
                if (isset($args[3]))
                    $this->createModelProperty($object[1], 'model_taxonomy', $args[3]);
                break;
            case 'commentmodel':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Model definition is missing.');
                $this->createModel($object[1], 'CommentModel');
                break;
            case 'js':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": JavaScript filename is missing.');
                $this->createAsset('js', $object[1], ['template' => isset($args[3]) ? $args[3] : 'asset']);
                break;
            case 'css':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": CSS filename is missing.');
                $this->createAsset('css', $object[1], ['template' => isset($args[3]) ? $args[3] : 'asset']);
                break;
            case 'sass':
            case 'scss':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": SASS filename is missing.');
                $importin = isset($args[3]) ? $args[3] : null;
                // Attempt to create master file first
                if ($importin)
                    $this->createAsset($object[0], $importin);
                $this->createAsset($object[0], $object[1], ['importin' => $importin]);
                break;
            case 'test':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": test name is missing.');
                $test = explode('@', $object[1]);
                $this->createTest($test[0], $args);
                // Create methods
                if (count($test) > 1)
                    foreach (array_slice($test, 1) as $test_method)
                        $this->createTestMethod($test[0], $test_method);
                break;
            case 'block':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": block name is missing.');
                $this->createBlock($object[1]);
                break;
        }
    }
}
