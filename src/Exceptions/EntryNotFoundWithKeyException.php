<?php

namespace Uvodo\Menv\Exceptions;

use Throwable;

/** @package Uvodo\Menv\Exceptions */
class EntryNotFoundWithKeyException extends Exception
{
    /**
     * @param string $key 
     * @param int $code 
     * @param Throwable|null $previous 
     * @return void 
     */
    public function __construct(
        string $key,
        int $code = 0,
        Throwable $previous = null
    ) {
        $msg = sprintf('Entry with key "%s" is not found.', $key);
        parent::__construct($msg, $code, $previous);
    }
}
