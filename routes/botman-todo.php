<?php

use App\Http\Conversations\ButtonConversation;
use App\Models\Todo;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\Drivers\Telegram\Extensions\Keyboard;
use BotMan\Drivers\Telegram\Extensions\KeyboardButton;
use VoIPForAll\Drivers\WhatsApp\WhatsAppDriver;

/** @var BotMan $botman */
$botman = app('botman');

//DriverManager::loadDriver(WhatsAppDriver::class);

$botman->hears('/start', function (BotMan $bot) {
    $bot->reply('👋 Hi ' . $bot->getUser()->getFirstName() . '!');
    $bot->reply(' I am the Build A Chatbot Todo bot');
    $bot->reply('You can use "add new todo" to add new todos');
});

$botman->hears('show my todos', function (BotMan $bot) {
    $bot->typesAndWaits(1);
    $todos = Todo::where('completed', false)
        ->where('user_id', $bot->getMessage()->getSender())
        ->get();
    if ($todos->count() > 0) {
        $bot->reply('Your todos are:');
        $bot->reply('ID - NAME');
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

$botman->hears("finish todo (.*)", function (BotMan $bot, $id) {
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

$botman->hears("delete todo (.*)", function (BotMan $bot, $id) {
    $todo = Todo::find($id);

    if (is_null($todo)) {
        $bot->reply("Sorry, I could not find the todo '$id'");
    } else {
        $todo->delete();
        $bot->reply("You successfully deleted todo \"{$todo->getAttribute('task')}\"!");
    }
});

$botman->fallback(function ($bot) {
    $message = $bot->getMessage();
    $bot->reply('Sorry, I do not understand *' . $message->getText() .  '*.');
    $bot->reply('Here is a list of commands I understand: ...');
    $bot->reply('/start');
    $bot->reply('show my todos');
    $bot->reply('add new todo {NAME}');
    $bot->reply('add new todo');
    $bot->reply('finish todo {ID}');
    $bot->reply('delete todo {ID}');
});
