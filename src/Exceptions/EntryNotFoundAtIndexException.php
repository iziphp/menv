<?php

namespace Uvodo\Menv\Exceptions;

use Throwable;

class EntryNotFoundAtIndexException extends Exception
{
    public function __construct(
        int $index,
        int $code = 0,
        Throwable $previous = null
    ) {
        $msg = sprintf('Entry is not found at index "%".', $index);
        parent::__construct($msg, $code, $previous);
    }
}
