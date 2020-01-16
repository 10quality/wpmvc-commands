<?php

namespace WPMVC\Commands;

use WPMVC\Commands\Base\BaseCommand as Command;
use WPMVC\Commands\Traits\RegisterWidgetTrait;
use WPMVC\Commands\Traits\CreateViewTrait;
use WPMVC\Commands\Traits\CreateModelTrait;
use WPMVC\Commands\Traits\CreateControllerTrait;
use Ayuco\Exceptions\NoticeException;
use WPMVC\Commands\Core\Builder;
use WPMVC\Commands\Visitors\AddMethodCallVisitor;

/**
 * Command that registers stuff into WordPress.
 * Registers widgets, post_types.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.2
 */
class RegisterCommand extends Command
{
    use RegisterWidgetTrait, CreateModelTrait, CreateControllerTrait, CreateViewTrait;

    /**
     * Command key.
     * @since 1.0.0
     * @var string
     */
    protected $key = 'register';

    /**
     * Command description.
     * @since 1.0.0
     * @var string
     */
    protected $description = 'Registers snippets and post types. i.e. ayuco register widget:MyWidget.';

    /**
     * Calls to command action.
     * @since 1.0.0
     * @since 1.0.1 Added suppot for assets and models.
     *
     * @param array $args Action arguments.
     */
    public function call($args = [])
    {
        if (count($args) == 0 || empty($args[2]))
            throw new NoticeException('Command "'.$this->key.'": Expecting an object to register (widget|type|model|asset).');

        $object = explode(':', $args[2]);

        // Validations
        if (!in_array($object[0], ['widget','type','model','asset']))
            throw new NoticeException('Command "'.$this->key.'": Invalid setting. Expecting (widget|type|model|asset).');

        switch ($object[0]) {
            case 'widget':
                // Validate second parameter
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Expecting a widget class name.');
                $this->registerWidget($object[1], $args);
                break;
            case 'type':
                // Validate second parameter
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Expecting a post type. I.e. "ayuco register type:book" (where "book" would be the type)');
                // Prepare
                $model = !isset($args[3]) || empty($args[3]) ? ucfirst($object[1]) : $args[3];
                $controller = !isset($args[4]) || empty($args[4]) ? null : $args[4];
                // Create controller
                $this->createModel($model);
                // Set properties
                $this->createModelProperty($model, 'type', $object[1]);
                $this->createModelProperty($model, 'aliases', []);
                if ($controller) {
                    $this->createModelProperty($model, 'registry_controller', $controller);
                    $this->createModelProperty($model, 'registry_metabox', ['title'=>'Meta fields','context'=>'normal','priority'=>'default']);
                    // Create controller
                    $this->createModelController($controller);
                    $this->createControllerProperty($controller, 'model', $this->config['namespace'].'\\Models\\'.$model);
                    // Create view
                    $this->createView('admin.metaboxes.'.$object[1].'.meta', 'metabox.php');
                }
                // Register model at bridge
                $builder = Builder::parser($this->rootPath.'/app/Main.php');
                $builder->addVisitor(new AddMethodCallVisitor($this->config, 'init', 'add_model', [$model]));
                $builder->build();
                break;
            case 'model':
                // Validate second parameter
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Expecting a model name.');
                // Register model at bridge
                $builder = Builder::parser($this->rootPath.'/app/Main.php');
                $builder->addVisitor(new AddMethodCallVisitor($this->config, 'init', 'add_model', [$object[1]]));
                $builder->build();
                // Print end
                $this->_print('Model registered!');
                $this->_lineBreak();
                break;
            case 'asset':
                // Validate second parameter
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Expecting an asset relative path.');
                // Register model at bridge
                $builder = Builder::parser($this->rootPath.'/app/Main.php');
                $builder->addVisitor(new AddMethodCallVisitor($this->config, 'init', 'add_asset', [$object[1]]));
                $builder->build();
                // Print end
                $this->_print('Asset registered!');
                $this->_lineBreak();
                break;
        }
    }
}
