<?php

namespace Nilnice\MiniSms\Exception;

use Exception;
use Throwable;

class InvalidSmsException extends Exception
{
    /**
     * InvalidSmsException constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
