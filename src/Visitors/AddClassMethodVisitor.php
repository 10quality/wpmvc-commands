<?php

namespace WPMVC\Commands\Visitors;

use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Param;
use PhpParser\Node\Name;
use PhpParser\Node\Expr\Variable;
use PhpParser\Comment;
use WPMVC\Commands\Base\NodeVisitor;

/**
 * Visits the node to add a new class method.
 *
 * @link https://github.com/nikic/PHP-Parser/blob/master/doc/2_Usage_of_basic_components
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.7
 */
class AddClassMethodVisitor extends NodeVisitor
{
    /**
     * Method's name to add.
     * @since 1.0.0
     * @var string
     */
    protected $methodName;

    /**
     * Method's parameters.
     * @since 1.0.0
     * @var array
     */
    protected $params = [];

    /**
     * Method's parameters.
     * @since 1.0.0
     * @var array
     */
    protected $comment = [];

    /**
     * Default constructor.
     * 
     * @param array  $config     Config
     * @param string $methodName Method name.
     * @param array  $params     Method parameters.
     * @param array  $comment    Method Comment.
     */
    public function __construct($config, $methodName, $params = [], $comment = '')
    {
        parent::__construct($config);
        $this->methodName = $methodName;
        $this->params = $params;
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
            // Build params
            $params = [];
            $paramComments = '';
            foreach ($this->params as $param) {
                if (is_array($param)) {
                    $params[] = isset($param['type']) && isset($param['name'])
                        ? new Param(new Variable($param['name']), null, new Name([$param['type']]))
                        : new Param(new Variable($param[0]));
                } else {
                    $params[] = new Param(new Variable($param));
                }
                $paramComments .= '    * @param '
                    .(isset($param['type']) ? $param['type'] : 'mixed')
                    .' $'.(isset($param['name']) ? $param['name'] : $param)."\n";
            }
            if (count($params) > 0)
                $paramComments = '     *'."\n".$paramComments;
            // ADD statement
            $node->stmts[] = new ClassMethod(
                preg_replace('/\-\./', '_', $this->methodName),
                [
                    'type'      => 1,
                    'params'    => $params,
                ],
                [
                    'comments'  => [new Comment(sprintf(
                        '/**'."\n"
                            .'     * Ayuco: %s'."\n"
                            .'     * @since '.$this->config['version']."\n"
                            .'     *'."\n"
                            . (empty($this->comment) ? '' : '    * '.$this->comment."\n")
                            .$paramComments
                            .'     * @return'."\n"
                            .'     */',
                        date('Y-m-d h:i a'))
                    )]
                ]
            );
        }
    }
}