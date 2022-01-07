<?php

namespace Uvodo\Menv\Exceptions;

use Throwable;

class EntryNotFoundWithKeyException extends Exception
{
    public function __construct(
        string $key,
        int $code = 0,
        Throwable $previous = null
    ) {
        $msg = sprintf('Entry with key "%s" is not found.', $key);
        parent::__construct($msg, $code, $previous);
    }
}
