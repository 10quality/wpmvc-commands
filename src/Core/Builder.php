<?php

namespace WPMVC\Commands\Core;

use PhpParser\Error;
use PhpParser\ParserFactory;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter;
use PhpParser\Node;
/**
 * PHP parser and builder.
 * Library class used to read and generate php files.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC
 * @version 1.0.0
 */
class Builder
{
    /**
     * Engine used to read or build php.
     * @since 1.0.0
     * @var mixed
     */
    protected $engine;

    /**
     * Node helper value.
     * @since 1.0.0
     * @var mixed
     */
    protected $node;

    /**
     * Inits builder as PHP generator.
     * @since 1.0.0
     */
    public static function builder()
    {
        $builder = new Builder;
        $builder->engine = new BuilderFactory;
        return $builder;
    }

    /**
     * Inits builder as PHP generator.
     * @since 1.0.0
     */
    public static function parser()
    {
        $builder = new Builder;
        $builder->engine = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        return $builder;
    }

    /**
     * Getter function.
     * @since 1.0.0
     *
     * @param string $property Property name.
     *
     * @return mixed
     */
    public function &__get($property)
    {
        if (property_exists($this, $property))
            return $this->$property;
        return;
    }

    /**
     * Setter function.
     * @since 1.0.0
     *
     * @param string $property Property name.
     * @param mixed  $value    Property value.
     */
    public function __set($property, $value)
    {
        if (property_exists($this, $property))
            $this->$property = $value;
    }
}