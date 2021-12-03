<?php

use App\Http\Conversations\ButtonConversation;
use App\Http\Conversations\OnboardConversation;
use App\Http\Middleware\ReceivedMiddleware;
use BotMan\BotMan\BotMan;

/** @var BotMan $botman */
$botman = app('botman');

$botman->middleware->received(new ReceivedMiddleware());

$botman->hears('(.*)', function (BotMan $bot) {
    $timestamp = $bot->getMessage()->getExtras('timestamp');
    $bot->reply($timestamp . ':' . $bot->getMessage()->getText());
});
