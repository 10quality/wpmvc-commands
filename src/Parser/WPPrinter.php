<?php

namespace WPMVC\Commands\Parser;

use PhpParser\PrettyPrinter\Standard as Printer;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Cast;
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Stmt;

/**
 * Custom pretty printer for PHP parser.
 * Formats code and applies as much WordPress coding standards as possible.
 * 
 * @link https://github.com/nikic/PHP-Parser/blob/master/doc/component/Pretty_printing.markdown
 * @link https://make.wordpress.org/core/handbook/best-practices/coding-standards/php/
 *
 * @author Ale Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.9.1
 */
class WPPrinter extends Printer
{
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pStmt_Unset
     */
    protected function pStmt_Unset(Stmt\Unset_ $node)
    {
        return 'unset( ' . $this->pCommaSeparated($node->vars) . ' );';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pStmt_Catch
     */
    protected function pStmt_Catch(Stmt\Catch_ $node)
    {
        return 'catch ( ' . $this->pImplode($node->types, '|') . ' '
             . $this->p($node->var)
             . ' ) {' . $this->pStmts($node->stmts) . $this->nl . '}';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pStmt_Switch
     */
    protected function pStmt_Switch(Stmt\Switch_ $node)
    {
        return 'switch ( ' . $this->p($node->cond) . ' ) {'
             . $this->pStmts($node->cases) . $this->nl . '}';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pStmt_Do
     */
    protected function pStmt_Do(Stmt\Do_ $node)
    {
        $multiline = $this->hasNodeReachedLineLength($node->cond);
        return 'do {' . $this->pStmts($node->stmts) . $this->nl
             . '} while ( ' . $this->p($node->cond, false, $multiline ? 'nl' : '') . ($multiline ? $this->nl : ' ') . ');';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pStmt_Foreach
     */
    protected function pStmt_While(Stmt\While_ $node)
    {
        $multiline = $this->hasNodeReachedLineLength($node->cond);
        return 'while ( ' . $this->p($node->cond, false, $multiline ? 'nl' : '') . ($multiline ? $this->nl : ' ') . ') {'
             . $this->pStmts($node->stmts) . $this->nl . '}';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pStmt_Foreach
     */
    protected function pStmt_Foreach(Stmt\Foreach_ $node)
    {
        return 'foreach ( ' . $this->p($node->expr) . ' as '
             . (null !== $node->keyVar ? $this->p($node->keyVar) . ' => ' : '')
             . ($node->byRef ? '&' : '') . $this->p($node->valueVar) . ' ) {'
             . $this->pStmts($node->stmts) . $this->nl . '}';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pStmt_For
     */
    protected function pStmt_For(Stmt\For_ $node)
    {
        return 'for ( '
             . $this->pCommaSeparated($node->init) . ';' . (!empty($node->cond) ? ' ' : '')
             . $this->pCommaSeparated($node->cond) . ';' . (!empty($node->loop) ? ' ' : '')
             . $this->pCommaSeparated($node->loop)
             . ' ) {' . $this->pStmts($node->stmts) . $this->nl . '}';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pStmt_ElseIf
     */
    protected function pStmt_ElseIf(Stmt\ElseIf_ $node)
    {
        $multiline = $this->hasNodeReachedLineLength($node->cond);
        return 'elseif ( ' . $this->p($node->cond, false, $multiline ? 'nl' : '') . ($multiline ? $this->nl : ' ') . ') {'
             . $this->pStmts($node->stmts) . $this->nl . '}';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pStmt_If
     */
    protected function pStmt_If(Stmt\If_ $node)
    {
        $multiline = $this->hasNodeReachedLineLength($node->cond);
        return 'if ( ' . $this->p($node->cond, false, $multiline ? 'nl' : '') . ($multiline ? $this->nl : ' ') . ') {'
             . $this->pStmts($node->stmts) . $this->nl . '}'
             . ($node->elseifs ? ' ' . $this->pImplode($node->elseifs, ' ') : '')
             . (null !== $node->else ? ' ' . $this->p($node->else) : '');
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pStmt_Declare
     */
    protected function pStmt_Declare(Stmt\Declare_ $node) {
        return 'declare ( ' . $this->pCommaSeparated($node->declares) . ' )'
             . (null !== $node->stmts ? ' {' . $this->pStmts($node->stmts) . $this->nl . '}' : ';');
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pStmt_Function
     */
    protected function pStmt_Function(Stmt\Function_ $node)
    {
        return 'function ' . ($node->byRef ? '&' : '') . $node->name
             . '(' . (count($node->params) ? ' ' : '') . $this->pCommaSeparated($node->params) . (count($node->params) ? ' ' : '') . ')'
             . (null !== $node->returnType ? ' : ' . $this->p($node->returnType) : '')
             . $this->nl . '{' . $this->pStmts($node->stmts) . $this->nl . '}';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pStmt_ClassMethod
     */
    protected function pStmt_ClassMethod(Stmt\ClassMethod $node)
    {
        return $this->pModifiers($node->flags)
             . 'function ' . ($node->byRef ? '&' : '') . $node->name
             . '(' . (count($node->params) ? ' ' : '') . $this->pCommaSeparated($node->params) . (count($node->params) ? ' ' : '') . ')'
             . (null !== $node->returnType ? ' : ' . $this->p($node->returnType) : '')
             . (null !== $node->stmts
                ? $this->nl . '{' . $this->pStmts($node->stmts) . $this->nl . '}'
                : ';');
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_Yield
     */
    protected function pExpr_Yield(Expr\Yield_ $node)
    {
        if ($node->value === null) {
            return 'yield';
        } else {
            // this is a bit ugly, but currently there is no way to detect whether the parentheses are necessary
            return '( yield '
                 . ($node->key !== null ? $this->p($node->key) . ' => ' : '')
                 . $this->p($node->value)
                 . ' )';
        }
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_Exit
     */
    protected function pExpr_Exit(Expr\Exit_ $node)
    {
        $kind = $node->getAttribute('kind', Expr\Exit_::KIND_DIE);
        return ($kind === Expr\Exit_::KIND_EXIT ? 'exit' : 'die')
             . (null !== $node->expr ? '( ' . $this->p($node->expr) . ' )' : '');
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_New
     */
    protected function pExpr_New(Expr\New_ $node)
    {
        if ($node->class instanceof Stmt\Class_) {
            $args = $node->args ? '(' . (count($node->args) ? ' ' : '') . $this->pMaybeMultiline($node->args) . (count($node->args) ? ' ' : '') . ')' : '';
            return 'new ' . $this->pClassCommon($node->class, $args);
        }
        return 'new ' . $this->p($node->class) . '(' . (count($node->args) ? ' ' : '') . $this->pMaybeMultiline($node->args) . (count($node->args) ? ' ' : '') . ')';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_ArrowFunction
     */
    protected function pExpr_ArrowFunction(Expr\ArrowFunction $node)
    {
        return ($node->static ? 'static ' : '')
            . 'fn' . ($node->byRef ? '&' : '')
            . '(' . (count($node->params) ? ' ' : '') . $this->pCommaSeparated($node->params) . (count($node->params) ? ' ' : '') . ')'
            . (null !== $node->returnType ? ': ' . $this->p($node->returnType) : '')
            . ' => '
            . $this->p($node->expr);
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_Closure
     */
    protected function pExpr_Closure(Expr\Closure $node)
    {
        return ($node->static ? 'static ' : '')
             . 'function ' . ($node->byRef ? '&' : '')
             . '(' . (count($node->params) ? ' ' : '') . $this->pCommaSeparated($node->params) . (count($node->params) ? ' ' : '') . ')'
             . (!empty($node->uses) ? ' use( ' . $this->pCommaSeparated($node->uses) . ' )' : '')
             . (null !== $node->returnType ? ' : ' . $this->p($node->returnType) : '')
             . ' {' . $this->pStmts($node->stmts) . $this->nl . '}';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_Array
     */
    protected function pExpr_Array(Expr\Array_ $node)
    {
        $syntax = $node->getAttribute('kind',
            $this->options['shortArraySyntax'] ? Expr\Array_::KIND_SHORT : Expr\Array_::KIND_LONG);
        if ($syntax === Expr\Array_::KIND_SHORT) {
            return '[' . (count($node->items) === 0 || $this->hasReachedLineLength($node->items) ? '' : ' ')
                . $this->pMaybeMultiline($node->items, true)
                . (count($node->items) === 0 || $this->hasReachedLineLength($node->items) ? '' : ' ') . ']';
        } else {
            return 'array(' . (count($node->items) === 0 || $this->hasReachedLineLength($node->items) ? '' : ' ')
                . $this->pMaybeMultiline($node->items, true)
                . (count($node->items) === 0 || $this->hasReachedLineLength($node->items) ? '' : ' ') . ')';
        }
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_Variable
     */
    protected function pExpr_Variable(Expr\Variable $node)
    {
        if ($node->name instanceof Expr) {
            return '${ ' . $this->p($node->name) . ' }';
        } else {
            return '$' . $node->name;
        }
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_List
     */
    protected function pExpr_List(Expr\List_ $node)
    {
        return 'list( ' . $this->pCommaSeparated($node->items) . ' )';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_Eval
     */
    protected function pExpr_Eval(Expr\Eval_ $node)
    {
        return 'eval( ' . $this->p($node->expr) . ' )';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_Isset
     */
    protected function pExpr_Isset(Expr\Isset_ $node)
    {
        return 'isset( ' . $this->pCommaSeparated($node->vars) . ' )';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_Empty
     */
    protected function pExpr_Empty(Expr\Empty_ $node)
    {
        return 'empty( ' . $this->p($node->expr) . ' )';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_StaticCall
     */
    protected function pExpr_StaticCall(Expr\StaticCall $node)
    {
        return $this->pDereferenceLhs($node->class) . '::'
             . ($node->name instanceof Expr
                ? ($node->name instanceof Expr\Variable
                   ? $this->p($node->name)
                   : '{ ' . $this->p($node->name) . ' }')
                : $node->name)
             . '(' . (count($node->args) ? ' ' : '') . $this->pMaybeMultiline($node->args) . (count($node->args) ? ' ' : '') . ')';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_MethodCall
     */
    protected function pExpr_MethodCall(Expr\MethodCall $node)
    {
        return $this->pDereferenceLhs($node->var) . '->' . $this->pObjectProperty($node->name)
             . '(' . (count($node->args) ? ' ' : '') . $this->pMaybeMultiline($node->args) . (count($node->args) ? ' ' : '') . ')';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_FuncCall
     */
    protected function pExpr_FuncCall(Expr\FuncCall $node)
    {
        return $this->pCallLhs($node->name)
             . '(' . (count($node->args) ? ' ' : '') . $this->pMaybeMultiline($node->args) . (count($node->args) ? ' ' : '') . ')';
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_Cast_Unset
     */
    protected function pExpr_Cast_Unset(Cast\Unset_ $node)
    {
        return $this->pPrefixOp(Cast\Unset_::class, '( unset ) ', $node->expr);
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_Cast_Bool
     */
    protected function pExpr_Cast_Bool(Cast\Bool_ $node)
    {
        return $this->pPrefixOp(Cast\Bool_::class, '( bool ) ', $node->expr);
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_Cast_Object
     */
    protected function pExpr_Cast_Object(Cast\Object_ $node) {
        return $this->pPrefixOp(Cast\Object_::class, '( object ) ', $node->expr);
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_Cast_Array
     */
    protected function pExpr_Cast_Array(Cast\Array_ $node)
    {
        return $this->pPrefixOp(Cast\Array_::class, '( array ) ', $node->expr);
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_Cast_String
     */
    protected function pExpr_Cast_String(Cast\String_ $node)
    {
        return $this->pPrefixOp(Cast\String_::class, '( string ) ', $node->expr);
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_Cast_Double
     */
    protected function pExpr_Cast_Double(Cast\Double $node)
    {
        $kind = $node->getAttribute('kind', Cast\Double::KIND_DOUBLE);
        if ($kind === Cast\Double::KIND_DOUBLE) {
            $cast = '( double )';
        } elseif ($kind === Cast\Double::KIND_FLOAT) {
            $cast = '( float )';
        } elseif ($kind === Cast\Double::KIND_REAL) {
            $cast = '( real )';
        }
        return $this->pPrefixOp(Cast\Double::class, $cast . ' ', $node->expr);
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pExpr_Cast_Int
     */
    protected function pExpr_Cast_Int(Cast\Int_ $node)
    {
        return $this->pPrefixOp(Cast\Int_::class, '( int ) ', $node->expr);
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinterAbstract@pPrec
     * 
     * @param mixed $mod Modification parameter.
     */
    protected function pPrec(\PhpParser\Node $node, int $parentPrecedence, int $parentAssociativity, int $childPosition, $mod = false, $level = 1) : string
    {
        $class = \get_class($node);
        if (isset($this->precedenceMap[$class])) {
            $childPrecedence = $this->precedenceMap[$class][0];
            if ($childPrecedence > $parentPrecedence
                || ($parentPrecedence === $childPrecedence && $parentAssociativity !== $childPosition)
            ) {
                $indent = '';
                for ($i = $level-1; $i >= 0; --$i) {
                    $indent .= '    ';
                }
                $level++;
                return '( ' . $this->p($node, false, $mod, $level) . ($mod === 'nl' ? $this->nl.$indent : ' ') . ')';
            }
        }
        return $this->p($node, false, $mod, $level);
    }
    /**
     * Overrride parent method.
     * @since 1.1.9
     * 
     * @see \PhpParser\PrettyPrinterAbstract@p
     * 
     * @param mixed $mod Modification parameter.
     */
    protected function p(Node $node, $parentFormatPreserved = false, $mod = false, $level = 1) : string
    {
        if (!$this->origTokens && $mod !== false) {
            return $this->{'p' . $node->getType()}($node, $mod, $level);
        }
        return parent::p($node, $parentFormatPreserved);
    }
    /**
     * Overrride parent method.
     * @since 1.1.9
     * 
     * @see \PhpParser\PrettyPrinterAbstract@pInfixOp
     * 
     * @param mixed $mod Modification parameter.
     */
    protected function pInfixOp(string $class, Node $leftNode, string $operatorString, Node $rightNode, $mod = false, $level = 1) : string {
        list($precedence, $associativity) = $this->precedenceMap[$class];

        return $this->pPrec($leftNode, $precedence, $associativity, -1, $mod, $level)
             . $operatorString
             . $this->pPrec($rightNode, $precedence, $associativity, 1, $mod, $level);
    }
    /**
     * Overrride parent method.
     * @since 1.1.9
     * 
     * @see \PhpParser\PrettyPrinter/Standard@pExpr_BinaryOp_BooleanAnd
     * 
     * @param mixed $mod   Modification parameter.
     * @param int   $level Indentation level.
     */
    protected function pExpr_BinaryOp_BooleanAnd(BinaryOp\BooleanAnd $node, $mod = false, $level = 1)
    {
        $indent = '';
        for ($i = $level-1; $i >= 0; --$i) {
            $indent .= '    ';
        }
        return $this->pInfixOp(BinaryOp\BooleanAnd::class, $node->left, ($mod === 'nl' ? $this->nl.$indent : ' ').'&& ', $node->right, $mod, $level);
    }
    /**
     * Overrride parent method.
     * @since 1.1.9
     * 
     * @see \PhpParser\PrettyPrinter/Standard@pExpr_BinaryOp_BooleanAnd
     * 
     * @param mixed $mod   Modification parameter.
     * @param int   $level Indentation level.
     */
    protected function pExpr_BinaryOp_BooleanOr(BinaryOp\BooleanOr $node, $mod = false, $level = 1)
    {
        $indent = '';
        for ($i = $level-1; $i >= 0; --$i) {
            $indent .= '    ';
        }
        return $this->pInfixOp(BinaryOp\BooleanOr::class, $node->left, ($mod === 'nl' ? $this->nl.$indent : ' ').'|| ', $node->right, $mod, $level);
    }
    /**
     * Overrride parent method.
     * @since 1.1.9.1
     * 
     * @see \PhpParser\PrettyPrinter/Standard@pExpr_Assign
     * 
     * @param mixed $mod Modification parameter.
     */
    protected function pExpr_Assign(Expr\Assign $node)
    {
        $lengthy = $this->hasNodeReachedLineLength($node->expr);
        return $this->pInfixOp(Expr\Assign::class, $node->var, ' = ', $node->expr, $lengthy ? 'nl' : false);
    }
    /**
     * Overrride parent method.
     * @since 1.1.9.1
     * 
     * @see \PhpParser\PrettyPrinter/Standard@pExpr_BinaryOp_Concat
     * 
     * @param mixed $mod   Modification parameter.
     * @param int   $level Indentation level.
     */
    protected function pExpr_BinaryOp_Concat(BinaryOp\Concat $node, $mod = false, $level = 1)
    {
        $indent = '';
        for ($i = $level-1; $i >= 0; --$i) {
            $indent .= '    ';
        }
        return $this->pInfixOp(BinaryOp\Concat::class, $node->left, ' .'.($mod === 'nl' ? $this->nl.$indent : ' '), $node->right, $mod, $level);
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@hasNodeWithComments
     */
    private function hasNodeWithComments(array $nodes)
    {
        foreach ($nodes as $node) {
            if ($node && $node->getComments()) {
                return true;
            }
        }
        return false;
    }
    /**
     * Overrride parent method.
     * @since 1.1.7
     * 
     * @see \PhpParser\PrettyPrinter\Standard@pMaybeMultiline
     */
    private function pMaybeMultiline(array $nodes, $trailingComma = false)
    {
        if (!$this->hasReachedLineLength($nodes) && !$this->hasNodeWithComments($nodes)) {
            return $this->pCommaSeparated($nodes);
        } else {
            return $this->pCommaSeparatedMultiline($nodes, $trailingComma) . $this->nl;
        }
    }
    /**
     * Returns flag indicating an array of nodes concatenated reachs the line length permitted.
     * @since 1.1.9
     * 
     * @param array $nodes
     * @param int   $allowed Allowed line length.
     * 
     * @return bool
     */
    private function hasReachedLineLength(array $nodes, $allowed = 60)
    {
        if (count($nodes) <= 2)
            return false;
        $string = '';
        foreach ($nodes as $node) {
            $string .= $this->p($node);
            if (strlen($string) > $allowed) {
                return true;
            }
        }
        return false;
    }
    /**
     * Returns flag indicating an array of nodes concatenated reachs the line length permitted.
     * @since 1.1.9
     * 
     * @param array $nodes
     * @param int   $allowed Allowed line length.
     * 
     * @return bool
     */
    private function hasNodeReachedLineLength(Node $node, $allowed = 60)
    {
        $string = $this->p($node);
        return strlen($string) > $allowed;
    }
}