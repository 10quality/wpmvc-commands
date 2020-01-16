<?php

namespace WPMVC\Commands;

use WPMVC\Commands\Traits\CreateViewTrait;
use WPMVC\Commands\Traits\CreateControllerTrait;
use WPMVC\Commands\Traits\HooksTrait;
use WPMVC\Commands\Base\BaseCommand as Command;
use Ayuco\Exceptions\NoticeException;
use WPMVC\Commands\Core\Builder;
use WPMVC\Commands\Visitors\AddMethodCallVisitor;

/**
 * Command that adds WordPress hooks into WordPress MVC.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.2
 */
class AddCommand extends Command
{
    use CreateViewTrait, CreateControllerTrait, HooksTrait;

    /**
     * Command key.
     * @since 1.0.0
     * @var string
     */
    protected $key = 'add';

    /**
     * Command description.
     * @since 1.0.0
     * @var string
     */
    protected $description = 'Adds WordPress hooks. i.e. ayuco add action:init AppController@init';

    /**
     * Calls to command action.
     * @since 1.0.0
     *
     * @param array $args Action arguments.
     */
    public function call($args = [])
    {
        if (count($args) == 0 || empty($args[2]))
            throw new NoticeException('Command "'.$this->key.'": Expecting a hook (action|filter|shortcode).');

        $object = explode(':', $args[2]);

        // Validations
        if (!in_array($object[0], ['action', 'filter', 'shortcode']))
            throw new NoticeException('Command "'.$this->key.'": Invalid hook. Expecting action, filter or shortcode.');

        if (!isset($object[1]))
            throw new NoticeException('Command "'.$this->key.'": Expecting a hook name. i.e. add:init (where "init" would be the hook name).');

        if (count($args) == 3)
            $args[] = 'AppController@'.$object[1];

        // View or controller
        $vc = explode('@', $args[3]);
        if (count($vc) == 1) {
            $vc[] = preg_replace('/\-\./', '_', $object[1]);
            $args[3] .= '@'.$vc[1];
        }
        switch ($vc[0]) {
            case 'view':
                $this->createView($vc[1], isset($args[4]) ? $args[4] : 'view.php');
                break;
            default:
                $this->createController($vc[0]);
                $this->createControllerMethod(
                    $vc[0],
                    $vc[1],
                    $this->getHookParams($object[1]),
                    ('@hook '.$object[1]."\n".'     *')
                );
                break;
        }

        // Add hook to bridge
        $builder = Builder::parser($this->rootPath.'/app/Main.php');
        $builder->addVisitor(new AddMethodCallVisitor($this->config, $this->getHookScope($object[1]), 'add_'.$object[0], [$object[1], $args[3]]));
        $builder->build();
    }
}
