<?php

namespace Uvodo\Menv\Exceptions;

use Throwable;

/** @package Uvodo\Menv\Exceptions */
class FileNotFoundException extends Exception
{
    /**
     * @param string $path 
     * @param int $code 
     * @param Throwable|null $previous 
     * @return void 
     */
    public function __construct(
        string $path,
        int $code = 0,
        Throwable $previous = null
    ) {
        $msg = sprintf('Env file at <%s> is neither exists or readable.', $path);
        parent::__construct($msg, $code, $previous);
    }
}
