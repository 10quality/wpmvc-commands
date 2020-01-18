<?php

namespace WPMVC\Commands\Traits;

use Exception;
use Ayuco\Exceptions\NoticeException;

/**
 * Trait used to update PHP commented information.
 *
 * @author Ale Mostajo <http://about.me/amostajo>
 * @copyright 10Quality <http://www.10quality.com>
 * @license MIT
 * @package WPMVC\Commands
 * @version 1.1.7
 */
trait UpdateCommentTrait
{
    /**
     * Sets a projects text domain.
     * @since 1.1.0
     *
     * @param string $domain Text domain.
     */
    public function updateComment($comment, $value, $filename)
    {
        try {
            $this->replaceInFile(
                '\*\s\@'.$comment.'[\s\S]+\n',
                '* @'.$comment.' '.$value."\n",
                $filename
            );
        } catch (Exception $e) {
            file_put_contents(
                $this->rootPath.'/error_log',
                $e->getMessage()
            );
            throw new NoticeException('Command "'.$this->key.'": Fatal error occurred.');
        }
    }
}