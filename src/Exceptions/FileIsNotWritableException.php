<?php

namespace Uvodo\Menv\Exceptions;

use Throwable;

class FileIsNotWritableException extends Exception
{
    public function __construct(
        string $path,
        int $code = 0,
        Throwable $previous = null
    ) {
        $msg = sprintf('Env file at <%s> is not writable.', $path);
        parent::__construct($msg, $code, $previous);
    }
}
