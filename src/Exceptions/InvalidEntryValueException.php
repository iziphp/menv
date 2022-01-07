<?php

namespace Uvodo\Menv\Exceptions;

use Throwable;

class InvalidEntryValueException extends Exception
{
    private $value;

    public function __construct(
        $value,
        int $code = 0,
        Throwable $previous = null
    ) {
        $this->value = $value;

        if (is_string($value)) {
            $msg = sprintf('<%s> is not a valid entry value.', $value);
        } else {
            $msg = 'Entry value is not valid.';
        }

        parent::__construct($msg, $code, $previous);
    }

    public function getValue()
    {
        return $this->value;
    }
}
