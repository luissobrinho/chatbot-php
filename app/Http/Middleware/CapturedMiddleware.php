<?php

namespace App\Http\Middleware;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Captured;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class CapturedMiddleware implements Captured
{

    /**
     * Handle a captured message.
     *
     * @param IncomingMessage $message
     * @param callable $next
     * @param BotMan $bot
     *
     * @return mixed
     */
    public function captured(IncomingMessage $message, $next, BotMan $bot)
    {
        $message->addExtras('in_conversation', true);
        return $next($message);
    }
}
