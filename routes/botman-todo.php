<?php

use App\Http\Conversations\ButtonConversation;
use App\Models\Todo;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;

/** @var BotMan $botman */
$botman = app('botman');

$botman->hears('/start', function ($bot) {
    $bot->reply('👋 Hi! I am the Build A Chatbot Todo bot');
    $bot->reply('You can use "add new todo" to add new todos');
});

$botman->hears('show my todos', function (BotMan $bot) {
    $bot->typesAndWaits(1);
    $todos = Todo::where('completed', false)
        ->where('user_id', $bot->getMessage()->getSender())
        ->get();
    if ($todos->count() > 0) {
        $bot->reply('Your todos are:');
        $todos->each(function (Todo $todo) use ($bot) {
            $kaybord = Keyboard::create()->addRow(
                KeyboardButton::create('Mark completed')
                    ->callbackData("finish todo {$todo->getAttribute('id')}"),
                KeyboardButton::create('Delete')
                    ->callbackData("delete todo {$todo->getAttribute('id')}")
            );
            $bot->reply("{$todo->getAttribute('id')} - {$todo->getAttribute('task')}",
                $kaybord->toArray());
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
