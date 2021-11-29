<?php

/** @var BotMan\BotMan\BotMan $botman */
$botman = app('botman');

$botman->hears('Hi', function ($bot) use ($botman) {
    $bot->typesAndWaits(2);
    $bot->reply('Hi there');
});

$botman->hears('How are you', function ($bot) {
    $bot->typesAndWaits(2);
    $bot->reply('I\', fine!');
});

$botman->hears('([0-9]) day forecast for (.*)', function ($bot, $days, $location) {
    $YOUR_ACCESS_KEY = getenv('WEATHERSTACK_KEY');
    $urlEncodeLocation = urlencode($location);
    $url = "http://api.weatherstack.com/forecast?access_key=${YOUR_ACCESS_KEY}&query=${urlEncodeLocation}";
    $response = json_decode(file_get_contents($url));

    $bot->reply("The weather in {$response->location->name}, {$response->location->country} is: ");
    $bot->reply("Forecast for the next ${days} days");

    foreach ($response->forecast as $forecastDay) {
        $bot->reply("Forecast for: {$forecastDay->date}");
        $bot->reply($forecastDay->astro->moon_phase);
    }

});

$botman->hears('Weather in (.*)', function ($bot, $location) {
    $YOUR_ACCESS_KEY = getenv('WEATHERSTACK_KEY');
    $urlEncodeLocation = urlencode($location);
    $url = "http://api.weatherstack.com/current?access_key=${YOUR_ACCESS_KEY}&query=${urlEncodeLocation}";
    $response = json_decode(file_get_contents($url));

    $bot->reply("The weather in {$response->location->name}, {$response->location->country} is: ");
    $bot->reply("Temperature: {$response->current->temperature}º Celsius");
});

//$botman->hears('Start conversation', \App\Http\Controllers\BotManController::class.'@startConversation');

$botman->fallback(function ($bot) {
    $message = $bot->getMessage();
    $bot->reply('Sorry, I do not understand <strong>' . $message->getText() .  '</strong>.');
    $bot->reply('Here is a list of commands I understand: ...');
    $bot->reply('Weather in {location}');
    $bot->reply('{days} day forecast for {location}');
    $bot->reply('Hi');
    $bot->reply('How are you');
});
