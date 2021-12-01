<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class OnboardConversation extends Conversation {

    protected string $name;
    protected string $age;

    public function run()
    {
        $this->say('Hello');
        $this->ask('What is your name?', function (Answer $answer) {
            $this->name = $answer->getText();
            $this->askAge();
        });
    }

    public function askAge()
    {
        $this->ask('What is your age?', function (Answer $answer) {
            $this->age = $answer->getText();
            $this->say('Nice to meet you, ' . $this->name);
            $this->say('Your age is, ' . $this->age);
        });
    }
}
