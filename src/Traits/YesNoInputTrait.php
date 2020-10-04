<?php

namespace WPMVC\Commands\Traits;

/**
 * Trait used to capture a Yes/No question.
 *
 * @author Alejandro Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.12
 */
trait YesNoInputTrait
{
    /**
     * Waits for an input.
     * Returns flag indicating if the input is yes.
     * @since 1.1.12
     * 
     * @return bool
     */
    private function getYesInput($question)
    {
        $this->_print($question.' [Y|N]:');
        $this->_lineBreak();
        $yes_no = strtolower( $this->listener->getInput() );
        return !empty( $yes_no ) && substr( $yes_no, 0, 1 ) === 'y';
    }

}