<?php

namespace NotYoel\sessions_example\sessions;

use pocketmine\player\Player;

use NotYoel\sessions_example\Main;

class SessionManager{

    /* This is where we store all the sessions, so then we can get them later.
       This array would look somewhat like this:
       [
            "Player1's UUID" => Player1's Session Class,
            "Player2's UUID" => Player2's Session Class,
            "Player3's UUID" => Player3's Session Class
       ]
    */
    private array $sessions = [];

    public function createSession(Player $player) : void{
        $uuid = $player->getUniqueId()->toString();
        $username = $player->getName();

        $db = Main::getInstance()->getDatabaseManager()->getDatabase();

        /* Here we get the player's data from the database.
           $result will return either an array containing the
           player's data or 'False' if the player isn't in
           the database yet, therefore, they've just logged into
           the server for the first time.
        */
        $result = $db->query("SELECT * FROM player WHERE uuid='{$uuid}'")->fetchArray(SQLITE3_ASSOC);
        if(is_array($result)){
            /* $result is an array, so it looks like this:
               [
                    "uuid" => Player's UUID,
                    "username" => Player's Username,
                    "money" => Player's Money
               ]
            */

            /* We get the player's money by indexing and assign it to a variable so then.
               we can use it when creating the session class down below.
            */
            $money = $result["money"];
        } else {
            // The player joined for the first time so $money is set to 0.
            $money = 0;

            /* Since they don't have any data in the database, we add it by
               inserting a new row into the database.
             */
            $db->exec("INSERT OR REPLACE INTO player(uuid, username, money) VALUES('{$uuid}', '{$username}'," . 0 . ")");
        }

        // We create a new 'Session' class and add it into the global 'sessions' property
        $session = new Session($uuid, $username, $money);
        $this->sessions[$uuid] = $session;
    }

    public function closeSession(Player $player) : void{
        /* The if statement is used so then if the player joins and quits on the
           "Locating server..." menu, this stops it from throwing an
           error. (Because normally, PlayerQuitEvent is called, therefore running closeSession() without
           a session being created, therefore attempting to close a non-existent session.)
        */
        if(($session = $this->getSession($player)) instanceof Session){
            // We get the player's data back.
            $uuid = $player->getUniqueId()->toString();
            $username = $player->getName();
            $money = $session->getMoney();

            // We add it back into the database before closing it.
            Main::getInstance()->getDatabaseManager()->getDatabase()->exec("INSERT OR REPLACE INTO player(uuid, username, money) VALUES('{$uuid}', '{$username}', {$money})");

            // We remove the player from the global 'sessions' property because they've left the server.
            unset($this->sessions[$player->getUniqueId()->toString()]);
        }
    }

    /* This can be used when trying to get a player's session, so then you can get stats like
       their money.
    */
    public function getSession(Player $player) : ?Session{
        return $this->sessions[$player->getUniqueId()->toString()] ?? null;
    }
}