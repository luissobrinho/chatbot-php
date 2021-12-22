<?php

use App\Http\Conversations\ButtonConversation;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Middleware\Dialogflow;

/** @var BotMan $botman */
$botman = resolve('botman');

$dialogFlow = Dialogflow::create(env('DIALOGFLOW_KEY'))->listenForAction();

$botman->middleware->received($dialogFlow);

$botman->hears('WeatherSearch', function (BotMan $bot) {
    $extras = $bot->getMessage()->getExtras();
    $location = $extras['apiParameters']['geo-city'];

    $YOUR_ACCESS_KEY = getenv('WEATHERSTACK_KEY');
    $urlEncodeLocation = urlencode($location);
    $url = "http://api.weatherstack.com/current?access_key=${YOUR_ACCESS_KEY}&query=${urlEncodeLocation}";
    $response = json_decode(file_get_contents($url));

    $bot->reply("The weather in {$response->location->name}, {$response->location->country} is: ");
    $bot->reply("Temperature: {$response->current->temperature}º Celsius");
});
