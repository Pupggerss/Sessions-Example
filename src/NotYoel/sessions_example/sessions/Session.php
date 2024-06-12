<?php

namespace NotYoel\sessions_example\sessions;

use pocketmine\player\Player;
use pocketmine\Server;

class Session{


    public function __construct(
        private string $xuid,
        private string $username,
        private int $money
    ){
    }

    public function getXuid() : string{
        return $this->xuid;
    }

    public function getUsername() : string{
        return $this->username;
    }

    public function getMoney() : int{
        return $this->money;
    }

    public function setMoney(int $money) : void{
        $this->money = $money;
    }

    public function getPlayer() : ?Player{
        return Server::getInstance()->getPlayerExact($this->username);
    }
}