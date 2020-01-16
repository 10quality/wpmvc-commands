<?php

namespace WPMVC\Commands\Base;

use PhpParser\NodeVisitorAbstract;

/**
 * Base command.
 *
 * @author Ale Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.6
 */
class NodeVisitor extends NodeVisitorAbstract
{
    /**
     * Apps config file.
     * @since 1.0.0
     * @var string
     */
    protected $config;

    /**
     * Default constructor.
     * 
     * @param array $config Config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }
}
