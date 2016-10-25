<?php

namespace WPMVC\Commands\Traits;

use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;

/**
 * Trait that provides value conversion in visitors.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.0.0
 */
trait VisitorValueTrait
{
    /**
     * Returns parsed/converted value.
     * @since 1.0.0
     *
     * @param mixed $value
     *
     * @return mixed
     */
    private function parseValue($value)
    {
        if (is_string($value))
            return new String_($value);
        if (is_bool($value))
            return new ConstFetch(new Name([$value ? 'true' : 'false']));
        if (is_float($value))
            return new DNumber($value);
        if (is_numeric($value))
            return new LNumber($value);
        if (is_array($value)) {
            $items = [];
            foreach ($value as $key => $value) {
                $items[] = new ArrayItem(
                    $this->parseValue($value),
                    $this->parseValue($key)
                );
            }
            return new Array_($items);
        }
        return null;
    }
    /**
     * Returns value type.
     * @since 1.0.0
     *
     * @param mixed $value
     *
     * @return string
     */
    private function getValueType($value)
    {
        if (is_string($value))
            return 'string';
        if (is_bool($value))
            return 'bool';
        if (is_float($value))
            return 'float';
        if (is_numeric($value))
            return 'int';
        if (is_array($value))
            return 'array';
        return 'mixed';
    }
}