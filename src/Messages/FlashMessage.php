<?php

namespace Luma\Framework\Messages;

class FlashMessage
{
    private string $message;

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