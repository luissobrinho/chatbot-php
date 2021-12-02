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
            $value = $answer->getText();

            if (trim($value) == '') {
                $this->repeat('This does not look like a real name, please provide your name.');
                return;
            }

            $this->name = $value;
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
