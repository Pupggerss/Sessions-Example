<?php

namespace NotYoel\sessions_example\sessions;

use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};

use NotYoel\sessions_example\Main;

class SessionListener implements Listener{

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();

        Main::getInstance()->getSessionManager()->createSession($player);

        Server::getInstance()->getLogger()->info("Created {$player->getName()}'s session.");
    }

    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        Main::getInstance()->getSessionManager()->closeSession($player);

        Server::getInstance()->getLogger()->info("Closed {$player->getName()}'s session.");
    }
}