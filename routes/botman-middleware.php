<?php

use App\Http\Conversations\ButtonConversation;
use App\Http\Conversations\OnboardConversation;
use App\Http\Middleware\CapturedMiddleware;
use App\Http\Middleware\HeardMiddleware;
use App\Http\Middleware\MatchingMiddleware;
use App\Http\Middleware\ReceivedMiddleware;
use App\Http\Middleware\SendingMiddleware;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

/** @var BotMan $botman */
$botman = app('botman');

//$botman->middleware->heard(new HeardMiddleware());
//$botman->middleware->captured(new CapturedMiddleware());
//$botman->middleware->received(new ReceivedMiddleware());
//$botman->middleware->sending(new SendingMiddleware());
//$botman->middleware->matching(new MatchingMiddleware());

//$botman->hears('(.*)', function (BotMan $bot) {
//    $timestamp = $bot->getMessage()->getExtras('timestamp');
//    $heard = $bot->getMessage()->getExtras('heard');
//    $in_conversation = $bot->getMessage()->getExtras('in_conversation');
//    $bot->reply($in_conversation . ':' . $bot->getMessage()->getText());
//});

$botman->hears('hi', function (BotMan $bot) {
    $timestamp = $bot->getMessage()->getExtras('timestamp');
    $heard = $bot->getMessage()->getExtras('heard');
    $in_conversation = $bot->getMessage()->getExtras('in_conversation');
    $bot->reply($in_conversation . ':' . $bot->getMessage()->getText());
});

$botman->hears('conversation', function (Botman $bot) {
   $bot->ask('How are you?', function (Answer $answer, $conversation) use ($bot) {
       $in_conversation = $conversation->bot->getMessage()->getExtras('in_conversation');
       $conversation->say($in_conversation . ':That\'s good...');
   });
});


$botman->hears('test', function (BotMan $bot) {
    $timestamp = $bot->getMessage()->getExtras('timestamp');
    $bot->reply($timestamp . ':' . $bot->getMessage()->getText());
});

$botman->fallback(function (Botman $bot) {
    $timestamp = $bot->getMessage()->getExtras('timestamp');
    $heard = $bot->getMessage()->getExtras('heard');
    $bot->reply($heard . ':' . 'I did not understand you');
});


$botman->hears('admin', function (BotMan $bot) {
    $bot->reply('Welcome to the admin section');
})->middleware(new MatchingMiddleware);
