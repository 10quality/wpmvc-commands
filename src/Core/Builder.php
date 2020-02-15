<?php

namespace WPMVC\Commands\Core;

use PhpParser\Error;
use PhpParser\Lexer\Emulative;
use PhpParser\ParserFactory;
use PhpParser\BuilderFactory;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\NodeVisitor\CloningVisitor;
use WPMVC\Commands\Parser\WPPrinter as Printer;

/**
 * PHP parser and builder.
 * Library class used to read and generate php files.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.10
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
     * Engine lexer.
     * @since 1.1.10
     * @var mixed
     */
    protected $lexer;

    /**
     * File statements.
     * @since 1.0.1
     * @var array
     */
    protected $stmts = [];

    /**
     * Flag that indicates if code formatting should be preserved.
     * @since 1.1.10
     * @var bool
     */
    protected $is_preserving = false;

    /**
     * Flag that indicates if build process is in debug mode or not.
     * @since 1.1.9
     * @var bool
     */
    protected $is_debug_mode = false;

    /**
     * Constructor.
     * @since 1.0.0
     *
     * @param string $filename
     * @param bool   $preserve
     * @param bool   $debug
     */
    public function __construct($filename, $preserve = false, $debug = false)
    {
        $this->filename = $filename;
        $this->is_debug_mode = $debug;
        $this->is_preserving = $preserve;
        $this->traverser = new NodeTraverser;
    }

    /**
     * Inits builder as PHP generator.
     * @since 1.0.0
     *
     * @param string $filename
     * @param bool   $debug
     */
    public static function builder($filename, $debug = false)
    {
        $builder = new self($filename, false, $debug);
        $builder->engine = new BuilderFactory;
        return $builder;
    }

    /**
     * Inits builder as PHP generator.
     * @since 1.0.0
     *
     * @param string $filename
     * @param bool   $preserve
     * @param bool   $debug
     */
    public static function parser($filename, $preserve = false, $debug = false)
    {
        $builder = new self($filename, $preserve, $debug);
        $builder->lexer = $preserve
            ? new Emulative([
                'usedAttributes' => [
                    'comments',
                    'startLine', 'endLine',
                    'startTokenPos', 'endTokenPos',
                ],
            ])
            : new Emulative;
        $builder->engine = (new ParserFactory)->create(ParserFactory::PREFER_PHP7, $builder->lexer);
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
        if ($this->is_debug_mode)
            $this->debug();
        $printer = new Printer;
        if ($this->is_preserving && $this->lexer && !empty($this->stmts)) {
            $this->traverser->addVisitor(new CloningVisitor());
            // Save into file
            file_put_contents(
                $this->filename,
                $printer->printFormatPreserving(
                    $this->traverser->traverse($this->stmts),
                    $this->stmts,
                    $this->lexer->getTokens()
                )
            );
        } else {
            $this->stmts = $this->traverser->traverse($this->stmts);
            // Save into file
            file_put_contents(
                $this->filename,
                $printer->prettyPrintFile($this->stmts)
            );
        }
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