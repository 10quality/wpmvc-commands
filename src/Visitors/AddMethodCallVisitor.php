<?php

namespace WPMVC\Commands\Visitors;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Arg;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Stmt\Nop;
use PhpParser\Comment;

/**
 * Visits the node to add a new line with method..
 *
 * @link https://github.com/nikic/PHP-Parser/blob/master/doc/2_Usage_of_basic_components
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.0
 */
class AddMethodCallVisitor extends NodeVisitorAbstract
{
    /**
     * Node name (ClassMethod) where to add the call.
     * @since 1.0.0
     * @var string
     */
    protected $nodeName;

    /**
     * Method's name to add.
     * @since 1.0.0
     * @var string
     */
    protected $methodName;

    /**
     * Method's arguments.
     * @since 1.0.0
     * @var array
     */
    protected $args = [];

    /**
     * Variable holding method.
     * @since 1.0.0
     * @var string
     */
    protected $variable;

    /**
     * Default constructor.
     * 
     * @param string $nodeName   Node name.
     * @param string $methodName Method name.
     * @param array  $args       Method arguments.
     * @param string $variable   Variable holding method.
     */
    public function __construct($nodeName, $methodName, $args = [], $variable = 'this')
    {
        $this->nodeName = $nodeName;
        $this->methodName = $methodName;
        $this->args = $args;
        $this->variable = $variable;
    }

    /**
     * On leave node event.
     * Adds extra statement before parser leaves node.
     * @since 1.0.0
     *
     * @param Node $node Node to check.
     */
    public function leaveNode(Node $node)
    {
        if ($node instanceof ClassMethod && $node->name === $this->nodeName) {
            // Build arguments.
            $args = [];
            foreach ($this->args as $arg) {
                $args[] = new Arg(
                    is_string($arg)
                        ? new String_($arg)
                        : (is_float($arg)
                            ? new DNumber($arg)
                            : new LNumber($arg)
                        ) 
                );
            }
            // ADD comment
            $nop = new Nop;
            $nop->setAttribute('comments', [
                new Comment(sprintf('// Ayuco: addition %s', date('Y-m-d h:i a')))
            ]);
            $node->stmts[] = $nop;
            // ADD statement
            $node->stmts[] = new MethodCall(
                empty($this->variable) ? null : new Variable($this->variable),
                $this->methodName,
                $args
            );
        }
    }
}