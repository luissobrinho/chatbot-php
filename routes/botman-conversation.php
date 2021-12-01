<?php

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

/** @var BotMan $botman */
$botman = app('botman');

$botman->hears('survey', function (BotMan $bot) {
    $bot->ask('What is your name?', function (Answer $answer, Conversation $conversation) {
        $value = $answer->getText();
        $conversation->say('Nice to meet you, ' . $value);

    });
});

$botman->hears('Luis', function (BotMan $bot) {
   $bot->reply('Hi there!');
});
