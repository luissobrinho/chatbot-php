<?php

namespace App\Http\Conversations;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class ButtonConversation extends Conversation {

    public function run()
    {
        $question = Question::create('What animal person are you?')
        ->addButtons([
            Button::create('I like cats')->value('cat'),
            Button::create('I like dogs')->value('dog')
        ]);

        $this->ask($question, function (Answer $answer) {
            if($answer->isInteractiveMessageReply()) {
                $this->say('You selected: ' . $answer->getValue());
            } else {
                $this->repeat();
            }

        });
    }
}
