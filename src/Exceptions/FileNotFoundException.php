<?php

namespace Uvodo\Menv\Exceptions;

use Throwable;

class FileNotFoundException extends Exception
{
    public function __construct(
        string $path,
        int $code = 0,
        Throwable $previous = null
    ) {
        $msg = sprintf('Env file at <%s> is neither exists or readable.', $path);
        parent::__construct($msg, $code, $previous);
    }
}
