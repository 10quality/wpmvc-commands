<?php

namespace WPMVC\Commands\Core;

use PhpParser\Error;
use PhpParser\ParserFactory;
use PhpParser\BuilderFactory;
use PhpParser\PrettyPrinter;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\PrettyPrinter\Standard as Printer;

/**
 * PHP parser and builder.
 * Library class used to read and generate php files.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.1
 */
class Builder
{
    /**
     * Engine used to read or build php.
     * @since 1.0.0
     * @var mixed
     */
    protected $builder;

    /**
     * Engine used to read or build php.
     * @since 1.0.0
     * @var mixed
     */
    protected $engine;

    /**
     * Code traverser.
     * @since 1.0.0
     * @var NodeTraverser
     */
    protected $traverser;

    /**
     * Filename.
     * @since 1.0.1
     * @var string
     */
    protected $filename;

    /**
     * File statements.
     * @since 1.0.1
     * @var array
     */
    protected $stmts = [];

    /**
     * Constructor.
     * @since 1.0.0
     *
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->traverser = new NodeTraverser;
    }

    /**
     * Inits builder as PHP generator.
     * @since 1.0.0
     *
     * @param string $filename
     */
    public static function builder($filename)
    {
        $builder = new self($filename);
        $builder->engine = new BuilderFactory;
        return $builder;
    }

    /**
     * Inits builder as PHP generator.
     * @since 1.0.0
     *
     * @param string $filename
     */
    public static function parser($filename)
    {
        $builder = new self($filename);
        $builder->engine = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
        // Parse
        $builder->parse();
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

    /**
     * Parses file and sets statements.
     * @since 1.0.1
     */
    public function parse()
    {
        $this->stmts = $this->engine->parse(file_get_contents($this->filename));
    }

    /**
     * Adds visitor to traverser.
     * @since 1.0.1
     *
     * @param NodeVisitorAbstract $visitor
     */
    public function addVisitor(NodeVisitorAbstract $visitor)
    {
        $this->traverser->addVisitor($visitor);
    }

    /**
     * Builds/generates file based on engine set.
     * @since 1.0.1
     */
    public function build()
    {
        $printer = new Printer;
        $this->stmts = $this->traverser->traverse($this->stmts);
        // Save into file
        file_put_contents(
            $this->filename,
            $printer->prettyPrintFile($this->stmts)
        );
    }

    /**
     * Prints statements in debug log.
     * @since 1.0.1 
     */
    private function debug()
    {
        ob_start();
        print_r($this->stmts);
        file_put_contents(__DIR__.'/../../debug.log', ob_get_clean());   
    }
}