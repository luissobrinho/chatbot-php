<?php

use App\Http\Conversations\ButtonConversation;
use App\Http\Conversations\OnboardConversation;
use App\Http\Middleware\HeardMiddleware;
use App\Http\Middleware\ReceivedMiddleware;
use App\Http\Middleware\SendingMiddleware;
use BotMan\BotMan\BotMan;

/** @var BotMan $botman */
$botman = app('botman');

$botman->middleware->heard(new HeardMiddleware());
$botman->middleware->received(new ReceivedMiddleware());
$botman->middleware->sending(new SendingMiddleware());

$botman->hears('hi', function (BotMan $bot) {
    $timestamp = $bot->getMessage()->getExtras('timestamp');
    $heard = $bot->getMessage()->getExtras('heard');
    $bot->reply($heard . ':' . $bot->getMessage()->getText());
});

$botman->fallback(function (Botman $bot) {
    $timestamp = $bot->getMessage()->getExtras('timestamp');
    $heard = $bot->getMessage()->getExtras('heard');
    $bot->reply($heard . ':' . 'I did not understand you');
});
