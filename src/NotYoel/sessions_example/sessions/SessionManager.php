<?php

namespace NotYoel\sessions_example\sessions;

use JetBrains\PhpStorm\Pure;
use pocketmine\player\Player;

use NotYoel\sessions_example\Main;

class SessionManager
{

    private array $sessions = [];

    public function createSession(Player $player): void
    {
        $xuid = $player->getXuid();
        $username = $player->getName();

        $db = Main::getInstance()->getDatabaseManager();

        $db->getMoney($xuid, function (array $moneyData) use ($xuid, $username, $db) {
            $playerMoney = $moneyData['money'] ?? 0;

            $session = new Session($xuid, $username, $playerMoney);
            $this->sessions[$xuid] = $session;
            $db->addPlayer($xuid, $username, $playerMoney);
        });
    }

    public function closeSession(Player $player) : void{
        if(($session = $this->getSession($player)) instanceof Session) {
            $xuid = $player->getXuid();
            $money = $session->getMoney();

            $db = Main::getInstance()->getDatabaseManager();
            $db->updateMoney($xuid, $money);
            unset($this->sessions[$player->getXuid()]);
        }
    }

    #[Pure] public function getSession(Player $player): ?Session
    {
        return $this->sessions[$player->getXuid()] ?? null;
    }
}