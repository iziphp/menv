<?php

namespace Uvodo\Menv\Exceptions;

use Throwable;

class InvalidEntryException extends Exception
{
    public function __construct(
        string $line,
        int $code = 0,
        Throwable $previous = null
    ) {
        $msg = sprintf('<%s> is not a valid entry line.', $line);
        parent::__construct($msg, $code, $previous);
    }
}
