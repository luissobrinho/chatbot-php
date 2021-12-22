<?php

use App\Http\Conversations\ButtonConversation;
use App\Models\Todo;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

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

$botman->hears("add new todo (.*)", function (BotMan $bot, $task) {
   Todo::create([
       'task' => $task,
   ]);
   $bot->reply("You added a new todo for '{$task}'");
});

$botman->hears("add new todo", function (BotMan $bot) {
    $bot->ask('Which task do you want to add?', function (Answer $answer, Conversation $conversation) {
        Todo::create([
            'task' => $answer,
        ]);
        $conversation->say("You added a new todo for '{$answer}'");
    });

});
