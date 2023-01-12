<?php

namespace NotYoel\sessions_example\database;

use NotYoel\sessions_example\Main;

class DatabaseManager{

    /* ik it's bad practice to implement a database like this, but
       this is an example so why not. */
    private \SQLite3 $database;

    public function __construct(){
        /* This is basically the same thing that I am doing in the Main class.
           We're assigning an instance of SQLite3 to a global property, so then
           we can access it later.
         */
        $this->database = new \SQLite3(Main::getInstance()->getDataFolder() . "Database.db");

        // Creates a table if there isn't one (This line excutes on the plugin's first ever run.)
        $this->database->exec("CREATE TABLE IF NOT EXISTS player(uuid TXT PRIMARY KEY, username TXT, money INT)");
    }

    public function getDatabase() : \SQLite3{
        return $this->database;
    }
}