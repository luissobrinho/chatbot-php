<?php

use App\Http\Conversations\ButtonConversation;
use App\Models\Todo;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

/** @var BotMan $botman */
$botman = app('botman');

$botman->hears('show my todos', function (BotMan $bot) {
    $todos = Todo::where('completed', false)
        ->where('user_id', $bot->getMessage()->getSender())
        ->get();
    if ($todos->count() > 0) {
        $bot->reply('Your todos are:');
        $todos->each(function (Todo $todo) use ($bot) {
            $bot->reply("{$todo->getAttribute('id')} - {$todo->getAttribute('task')}");
        });
    } else {
        $bot->reply('You do not have any todos.');
    }
});

$botman->hears("add new todo (.*)", function (BotMan $bot, $task) {
   Todo::create([
       'task' => $task,
       'user_id' => $bot->getMessage()->getSender(),
   ]);
   $bot->reply("You added a new todo for '{$task}'");
});

$botman->hears("add new todo", function (BotMan $bot) {
    $bot->ask('Which task do you want to add?', function (Answer $answer, Conversation $conversation) {
        Todo::create([
            'task' => $answer,
            'user_id' => $conversation->getBot()->getMessage()->getSender(),
        ]);
        $conversation->say("You added a new todo for '{$answer}'");
    });
});

$botman->hears("finish todo ([0-9])", function (BotMan $bot, $id) {
    $todo = Todo::find($id);

    if (is_null($todo)) {
        $bot->reply("Sorry, I could not find the todo '$id'");
    } else {
        $todo->update([
            'completed' => true,
        ]);

        $bot->reply("Whohoo, you finished \"{$todo->getAttribute('task')}\"!");
    }
});

$botman->hears("delete todo ([0-9])", function (BotMan $bot, $id) {
    $todo = Todo::find($id);

    if (is_null($todo)) {
        $bot->reply("Sorry, I could not find the todo '$id'");
    } else {
        $todo->delete();
        $bot->reply("You successfully deleted todo \"{$todo->getAttribute('task')}\"!");
    }
});
