<?php
use BotMan\BotMan\Messages\Attachments\Audio;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Attachments\Video;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

/** @var BotMan\BotMan\BotMan $botman */
$botman = app('botman');

$botman->hears('/gif (.*)', function($bot, $name) {
    $KEY = getenv('GIPHY_KEY');
    $url = "https://api.giphy.com/v1/gifs/search?api_key=${KEY}&q=$name&limit=25&offset=0&rating=g&lang=pt";
    $results = json_decode(file_get_contents($url));
    $image = $results->data[rand(0, 25)]->images->downsized_large->url;
    $message = OutgoingMessage::create($results->data[0]->title)->withAttachment(
        new Image($image)
    );

    $bot->reply($message);
});

$botman->hears('video', function($bot) {
    $message = OutgoingMessage::create('This is your audio message')->withAttachment(
        new Video('https://www.w3schools.com/html/mov_bbb.mp4')
    );

    $bot->reply($message);
});

$botman->hears('audio', function($bot) {
    $message = OutgoingMessage::create('This is your video')->withAttachment(
        new Audio('https://www.w3schools.com/html/horse.ogg')
    );

    $bot->reply($message);
});
