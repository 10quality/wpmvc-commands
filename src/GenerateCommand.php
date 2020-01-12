<?php

namespace WPMVC\Commands;

use WPMVC\Commands\Traits\GeneratePotTrait;
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
 * @version 1.1.2
 */
class GenerateCommand extends Command
{
    use GeneratePotTrait;

    /**
     * Command key.
     * @since 1.0.0
     * @var string
     */
    protected $key = 'generate';

    /**
     * Command description.
     * @since 1.0.0
     * @var string
     */
    protected $description = 'Generates files, like the language Pot file [in-progress, has bugs].';

    /**
     * Calls to command action.
     * @since 1.1.0
     *
     * @param array $args Action arguments.
     */
    public function call($args = [])
    {
        if (count($args) == 0 || empty($args[2]))
            throw new NoticeException('Command "'.$this->key.'": Expecting a file to generate (pot).');

        $object = explode(':', $args[2]);

        switch ($object[0]) {
            case 'pot':
                $textdomain = !isset($args[3]) || empty($args[3]) ? null : $args[3];
                $lang = !isset($args[4]) || empty($args[4]) ? 'en' : $args[4];
                $this->generatePot($textdomain, $lang);
                break;
        }
    }
}