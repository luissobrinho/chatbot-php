<?php

namespace App\Http\Middleware;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Heard;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class HeardMiddleware implements Heard
{

    /**
     * @param IncomingMessage $message
     * @param $next
     * @param BotMan $bot
     * @return mixed|void
     */
    public function heard(IncomingMessage $message, $next, BotMan $bot)
    {
        $message->addExtras('heard', true);
        return $next($message);
    }
}
