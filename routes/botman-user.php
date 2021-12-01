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

$botman->hears('infromation', function(\BotMan\BotMan\BotMan $bot) {
    $user = $bot->getUser();

    $bot->reply('User: ' . $user->getId());
    $bot->reply('Fistname: ' . $user->getFirstName());
    $bot->reply('Lastname: ' . $user->getLastName());
    $bot->reply('Username: ' . $user->getUsername());
    $bot->reply('Info: ' . print_r($user->getInfo(), true));
});
