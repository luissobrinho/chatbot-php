<?php

use App\Http\Conversations\ButtonConversation;
use BotMan\BotMan\BotMan;

/** @var BotMan $botman */
$botman = app('botman');

$botman->hears('conversation', function (BotMan $bot) {
    $bot->startConversation(new ButtonConversation());
});
