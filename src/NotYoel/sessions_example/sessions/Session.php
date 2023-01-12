<?php

namespace NotYoel\sessions_example\sessions;

use pocketmine\player\Player;
use pocketmine\Server;

class Session{

    /* Here we store all the player's data.
       This data can be modified while their online
       Ex: Their Money can change if you use the '/setbalance' command.

       When they leave we get all of this data back and add it into the
       database.
    */

    public function __construct(
        private string $uuid,
        private string $username,
        private int $money
    ){
    }

    public function getUuid() : string{
        return $this->uuid;
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