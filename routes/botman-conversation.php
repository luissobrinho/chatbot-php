<?php

use App\Http\Conversations\ButtonConversation;
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

$botman->hears('Help', function (BotMan $bot) {
   $bot->reply('This is the helping information.');
})->skipsConversation();


$botman->hears('stop', function (BotMan $bot) {
   $bot->reply('We stopped your conversation.');
})->stopsConversation();
