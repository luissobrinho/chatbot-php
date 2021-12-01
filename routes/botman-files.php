<?php

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Attachments\Audio;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Attachments\Video;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;

/** @var BotMan $botman */
$botman = app('botman');

$botman->receivesImages(function (BotMan $bot, Image $images) {
    $image = $images[0];

    $bot->reply(OutgoingMessage::create('I received your image')->withAttachment($image));
});

$botman->receivesAudio(function (BotMan $bot, Audio $audios) {
    $audio = $audios[0];

    $bot->reply(OutgoingMessage::create('I received your audio')->withAttachment($audio));
});

$botman->receivesVideos(function (BotMan $bot, Video $videos) {
    $video = $videos[0];

    $bot->reply(OutgoingMessage::create('I received your video')->withAttachment($video));
});

$botman->receivesFiles(function (BotMan $bot, $files) {

    $bot->reply('I received your file');
});
