<?php

namespace WPMVC\Commands;

use WPMVC\Commands\Base\BaseCommand as Command;
use WPMVC\Commands\Traits\RegisterWidgetTrait;
use Ayuco\Exceptions\NoticeException;

/**
 * Command that registers stuff into wordpress.
 * Registers widgets, post_types.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.0
 */
class RegisterCommand extends Command
{
    use RegisterWidgetTrait;

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
     *
     * @param array $args Action arguments.
     */
    public function call($args = [])
    {
        if (count($args) == 0 || empty($args[2]))
            throw new NoticeException('Command "'.$this->key.'": Expecting an object to resigter (widget|post_type).');

        $object = explode(':', $args[2]);

        switch ($object[0]) {
            case 'widget':
                if (!isset($object[1]) || empty($object[1]))
                    throw new NoticeException('Command "'.$this->key.'": Expecting a widget class name.');
                $this->registerWidget($object[1], $args);
                break;
            case 'post_type':
                # code...
                break;
        }
    }
}