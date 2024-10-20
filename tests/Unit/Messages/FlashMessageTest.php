<?php

namespace Luma\Tests\Unit\Messages;

use Luma\Framework\Messages\FlashMessage;
use PHPUnit\Framework\TestCase;

class FlashMessageTest extends TestCase
{
    public function testText(): void
    {
        $flashMessage = new FlashMessage('Informational message');

        self::assertEquals('Informational message', $flashMessage->text());
    }
}