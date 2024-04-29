<?php

namespace Luma\Framework\Messages;

class FlashMessage
{
    private string $message;

    public const INFO = 'info';
    public const ERROR = 'error';
    public const SUCCESS = 'success';
    public const NOTICE = 'notice';
    public const WARNING = 'warning';

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function text(): string
    {
        return $this->message;
    }
}