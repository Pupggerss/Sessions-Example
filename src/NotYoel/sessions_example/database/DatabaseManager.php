<?php

namespace NotYoel\sessions_example\database;

use Closure;
use NotYoel\sessions_example\Main;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use poggit\libasynql\SqlError;

class DatabaseManager
{
    private DataConnector $database;

    /**
     * @return DataConnector
     */
    public function getDatabase(): DataConnector
    {
        return $this->database;
    }

    public function loadDatabase(): void
    {
        $this->database = libasynql::create(Main::getInstance(), Main::getInstance()->getConfig()->get('database'), [
            "sqlite" => "sqlite.sql",
        ]);
        $this->database->executeGeneric('init.players');
        $this->database->waitAll();
    }

    public function addPlayer(string $xuid, string $username, int $money): void
    {
        $this->database->executeInsert('add.player', [
            'xuid' => $xuid,
            'username' => $username,
            'money' => $money
        ], null,
            fn(SqlError $err) => Main::getInstance()->getServer()->getLogger()->error($err->getMessage()));
    }


    public function getMoney(string $xuid, Closure $callback): void
    {
        $this->database->executeSelect(
            'get.money', [
            'xuid' => $xuid
        ], function (array $rows) use ($callback) {
            $moneyData = $rows[0] ?? ['money' => 0];
            $callback($moneyData);
        }
        );
    }

    public function updateMoney(string $xuid, int $money): void
    {
        $this->database->executeChange('update.money', [
            'xuid' => $xuid,
            'money' => $money
        ], null,
            fn(SqlError $err) => Main::getInstance()->getServer()->getLogger()->error($err->getMessage()));
    }
}