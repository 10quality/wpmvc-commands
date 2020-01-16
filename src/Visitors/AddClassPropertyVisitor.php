<?php

namespace WPMVC\Commands\Visitors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\TraitUse;
use PhpParser\Node\Stmt\Property;
use PhpParser\Node\Stmt\PropertyProperty;
use PhpParser\Comment;
use WPMVC\Commands\Traits\VisitorValueTrait;
use WPMVC\Commands\Base\NodeVisitor;

/**
 * Visits the node to add a new property.
 *
 * @link https://github.com/nikic/PHP-Parser/blob/master/doc/2_Usage_of_basic_components
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.0
 */
class AddClassPropertyVisitor extends NodeVisitor
{
    use VisitorValueTrait;

    /**
     * Property name.
     * @since 1.0.0
     * @var string
     */
    protected $name;

    /**
     * Property value.
     * @since 1.0.0
     * @var mixed
     */
    protected $value;

    /**
     * Property type (1=public 2=protected).
     * @since 1.0.0
     * @var int
     */
    protected $type;

    /**
     * Comment.
     * @since 1.0.0
     * @var string
     */
    protected $comment;

    /**
     * Default constructor.
     * 
     * @param array  $config  Config
     * @param string $name    Name.
     * @param mixed  $value   Value.
     * @param int    $type    Type (public, private or protected)
     * @param string $comment Comment.
     */
    public function __construct($config, $name, $value = null, $type = 2, $comment = '')
    {
        parent::__construct($config);
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
        $this->comment = $comment;
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
        if ($node instanceof Class_) {
            $node->stmts[] = new Property(
                $this->type,
                [new PropertyProperty(
                    $this->name,
                    $this->parseValue($this->value)
                )],
                [
                    'comments'  => [new Comment(sprintf(
                        '/**'."\n"
                            .'     * Property %s.'."\n"
                            . (empty($this->comment) ? '' : '    * '.$this->comment."\n")
                            .'     * Ayuco: addition %s'."\n"
                            .'     * @since '.$this->config['version']."\n"
                            .'     *'."\n"
                            .'     * @var '.$this->getValueType($this->value)."\n"
                            .'     */',
                        $this->name,
                        date('Y-m-d h:i a'))
                    )]
                ]
            );
        }
    }
}