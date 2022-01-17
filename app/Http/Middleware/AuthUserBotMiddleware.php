<?php

namespace App\Http\Middleware;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Matching;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class AuthUserBotMiddleware implements Matching
{
    private BotMan $botMan;

    public function __construct(BotMan $botMan)
    {
        $this->botMan = $botMan;
    }

    /**
     * @param IncomingMessage $message
     * @param $pattern
     * @param $regexMatched
     * @return bool
     */
    public function matching(IncomingMessage $message, $pattern, $regexMatched): bool
    {
        $isLogged = $this->botMan->userStorage()->find('id')->isEmpty();
        $isOptions = $this->botMan->userStorage()->find('option')->isEmpty();
//        $this->botMan->userStorage()->delete('id');
//        $this->botMan->userStorage()->delete('option');
        return $isLogged && $isOptions && $regexMatched;
    }
}
