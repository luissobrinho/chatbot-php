<?php

namespace App\Http\Middleware;

use BotMan\BotMan\Interfaces\Middleware\Matching;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class MatchingMiddleware implements Matching
{
    const ADMIN_ID = 'LOGGED_USER';

    /**
     * @param IncomingMessage $message
     * @param $pattern
     * @param $regexMatched
     * @return bool
     */
    public function matching(IncomingMessage $message, $pattern, $regexMatched): bool
    {
        $isAdministrator = $message->getSender() === self::ADMIN_ID;
        return $isAdministrator && $regexMatched;
    }
}
