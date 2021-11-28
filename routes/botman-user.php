<?php
/** @var BotMan\BotMan\BotMan $botman */
$botman = app('botman');

/**
 * TODO sempre que é chamado ele envia o reply
 */
//$botman->reply('HI');


$botman->hears('My name is (.*)', function ($bot, $name) {
    $bot->userStorage()->save([
        'name' => $name,
    ]);
    $bot->reply("Hello ${name}");
});

$botman->hears('Say my name', function ($bot) {
    $name = $bot->userStorage()->get('name');
    $bot->reply("Your name is ${name}");
});
