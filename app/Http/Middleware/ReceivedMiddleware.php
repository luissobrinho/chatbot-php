<?php

namespace App\Http\Middleware;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Closure;
use Illuminate\Http\Request;

class ReceivedMiddleware implements Received
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * @param IncomingMessage $message
     * @param $next
     * @param BotMan $bot
     * @return mixed|void
     */
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        $original = $message->getText();
        $message->setText($original . ' <- you said that.');
        $message->addExtras('timestamp', time());
        return $next($message);
    }
}
