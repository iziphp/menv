<?php

namespace Uvodo\Menv\Exceptions;

use Throwable;

/** @package Uvodo\Menv\Exceptions */
class EntryNotFoundAtIndexException extends Exception
{
    /**
     * @param int $index 
     * @param int $code 
     * @param Throwable|null $previous 
     * @return void 
     */
    public function __construct(
        int $index,
        int $code = 0,
        Throwable $previous = null
    ) {
        $msg = sprintf('Entry is not found at index "%".', $index);
        parent::__construct($msg, $code, $previous);
    }
}
