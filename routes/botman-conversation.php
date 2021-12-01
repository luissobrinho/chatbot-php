<?php

use App\Http\Conversations\OnboardConversation;
use BotMan\BotMan\BotMan;

/** @var BotMan $botman */
$botman = app('botman');

$botman->hears('survey', function (BotMan $bot) {
    $bot->startConversation(new OnboardConversation());
});

$botman->hears('Luis', function (BotMan $bot) {
   $bot->reply('Hi there!');
});
