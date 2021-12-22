<?php

use App\Http\Conversations\ButtonConversation;
use App\Models\Todo;
use BotMan\BotMan\BotMan;

/** @var BotMan $botman */
$botman = app('botman');

$botman->hears('show my todos', function (BotMan $bot) {
    $todos = Todo::all();
    if ($todos->count() > 0) {
        $bot->reply('Your todos are:');
        $todos->each(function (Todo $todo) use ($bot) {
            $bot->reply($todo->getAttribute('task'));
        });
    } else {
        $bot->reply('You do not have any todos.');
    }
});
