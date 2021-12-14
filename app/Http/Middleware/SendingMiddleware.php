<?php

namespace App\Http\Middleware;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Sending;

class SendingMiddleware implements Sending
{

    public function sending($payload, $next, BotMan $bot)
    {
        // TODO: Implement sending() method.
        $text = $payload['message']->getText();
        $payload['message']->text(time() . ':' . $text);
        return $next($payload);
    }
}
