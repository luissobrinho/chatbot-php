<?php


use App\Http\Conversations\ConnectConversation;
use App\Http\Middleware\AuthUserBotMiddleware;
use BotMan\BotMan\BotMan;

/** @var BotMan $botman */
$botman = app('botman');

$botman->group(['middleware' => new AuthUserBotMiddleware($botman)], function ($botman) {
    $botman->hears('(.*)', function (BotMan $bot) {
        $bot->startConversation(new ConnectConversation());
    });
});
