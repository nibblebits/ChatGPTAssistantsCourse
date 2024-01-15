<?php

/*
 * Licensed under GPLv2
 * Author: Daniel McCarthy
 * Email: daniel@dragonzap.com
 * Dragon Zap Publishing
 * Website: https://dragonzap.com
 */
namespace Dragonzap\OpenAI\ChatGPT\Exceptions;

use Exception;

/**
 * Thrown in cases where there was an error for a OpenAI run.
 */
class ThreadRunResponseLastError extends Exception
{

    protected $code;
    public function __construct($message = "", $code = '', $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->code = $code;

    }

    /**
     * Returns the ChatGPT error code i.e rate_limit_exceeded
     */
    public function getErrorCode(): string
    {
        return $this->code;
    }

}

