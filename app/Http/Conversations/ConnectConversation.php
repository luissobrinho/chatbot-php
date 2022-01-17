<?php

namespace App\Http\Conversations;

use App\Models\Menu;
use App\Models\User;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use Illuminate\Database\Eloquent\Collection;

class ConnectConversation extends Conversation {

    public function run()
    {
        $this->acceptTerms();
    }

    private function acceptTerms() {
        $this->say('Para utilizar o nosso assistente você deve aceitar os termos de uso disponíveis no link abaixo. <a href="https://www.centraisvoip.com.br/politica-de-uso/" target="_blank">https://www.centraisvoip.com.br/politica-de-uso/</a>');
        $this->ask($this->convertToSay('Você aceita? Digite uma das opções:\n*1* - Sim\n*2* - Não.'), [
            array(
                'pattern' => '1',
                'callback' => function () {
                    $this->say('Olá! Eu me chamo Alex, sou atendente virtual da Centrais VoIP!');
                    $this->say('Não identificamos o seu usuário, então vou solicitar algumas informações.');
                    $this->say('Caso não seja um dos nossos clientes ligue para 0800222000');
                    $auth = 'email';
                    switch ($auth) {
                        case 'CPF':
                            $this->authCPF();
                            break;
                        default:
                            $this->authEmail();
                            break;
                    }
                }
            ),
            [
                'pattern' => '2',
                'callback' => function () {
                    $this->say('Ops! Infelizmente não posso te atender.');
                    $this->ask($this->convertToSay('mas se deseja tentar novamente digite *1*'), [
                        [
                            'pattern' => '1',
                            'callback' => function () {
                                $this->acceptTerms();
                            }
                        ],
                    ]);

                }
            ]
        ]);
    }

    private function authEmail() {
        $this->ask('Informe seu e-mail', function(Answer $answer) {
            if (trim($answer->getText()) !== '') {
                $user = User::where('email', $answer->getText())->first();
                if (!$user) {
                    $this->say('Não identificamos o seu e-mail em nossa base.');
                    $this->say('Por favor, entre em contato com 0800222000.');
                    return;
                }

                $this->getBot()->userStorage()->save([
                    'id' => $user->id,
                ], 'id');
                $this->say('Olá ' . $user->name);
                $menus = Menu::where('menu_id', null)->orderBy('option')->get();
                $this->loadMenu($menus);

            }
        });
    }

    private function authCPF() {
        $this->ask('Informe seu CPF', function(Answer $answer) {
            if (trim($answer->getText()) !== '') {
                $user = User::where('cpf', $answer->getText())->first();
                if (!$user) {
                    $this->say('Não identificamos o seu CPF em nossa base.');
                    $this->say('Por favor, entre em contato com 0800222000.');
                    return;
                }

                $this->getBot()->userStorage()->save([
                    'id' => $user->id,
                ], 'id');
                $this->say('Olá ' . $user->name . '!');
                $menus = Menu::where('menu_id', null)->orderBy('option')->get();
                $this->loadMenu($menus);
            }
        });
    }

    private function convertToSay(string $string): string {
        if ($this->getBot()->getDriver()->getName() == 'Web') {
            $string = implode("<br />", explode('\n', $string));
            $arrString = explode('*', $string);
            if (count($arrString) > 0) {
                $newString = '';
                foreach ($arrString as $key => $val) {
                    $newString .= ($key % 2 == 0) ? $val . '<strong>' : $val . '</strong>';
                }
            return $newString;
            } else {
                return $string;
            }
        }
        return $string;
    }

    private function loadMenu(Collection $menus) {
        $menus->each(function($menu) {
            if ($menu->type == 'info') {
                $question = $menu->question;
                if (!$menu->menus->isEmpty()) {
                    $menu->menus->each(function($option) use (&$question) {
                        if ($option->type == 'option') {
                            $question .=  "\\n*$option->option* - $option->question";
                        }
                    });
//                    $this->getBot()->typesAndWaits(2);
                    $this->ask($this->convertToSay($question), function(Answer $answer) use ($menu, $question) {
                        $menus = $menu->menus->first(function($option) use ($answer) {
                            return $option->option === $answer->getText();
                        });

                        if (!$menus) {
//                            $this->getBot()->typesAndWaits(2);
                            $this->repeat('Ops! Não entendi, vamos tentar novamente.');
                            $this->say($this->convertToSay($question));
                            return;
                        }
                        if (!$menus->finish) {
                            $this->loadMenu($menus->menus);
                        } else {
                            $this->getBot()->userStorage()->save([
                                'finished' => true,
                            ], 'option');
                        }
                    });

                } else {
                    if (!$menu->finish) {
//                        $this->getBot()->typesAndWaits(2);
                        $this->say($this->convertToSay($question));
                    } else {
                        $this->say($this->convertToSay($question));
                        $this->getBot()->userStorage()->save([
                            'finished' => true,
                        ], 'option');
                    }
                }
            }
        });
    }

    public function stopsConversation(IncomingMessage $message): bool
    {
        if ($message->getText() == 'stop') {
            $this->getBot()->userStorage()->delete('id');
            return true;
        }

        return false;
    }

    public function skipsConversation(IncomingMessage $message): bool
    {
        if ($message->getText() == 'pause') {
            return true;
        }

        return false;
    }

}
