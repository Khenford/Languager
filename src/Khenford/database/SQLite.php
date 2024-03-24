<?php

declare(strict_types=1);

namespace Khenford\database;

use Khenford\Languager;
class SQLite{

    private static \SQLite3 $database;

    public function __construct(Languager $plugin, string $name){
        if(!is_dir($plugin->getDataFolder())){
            @mkdir($plugin->getDataFolder());
        }

        self::$database = new \SQLite3($plugin->getDataFolder().$name);
        $this->initDataBase();
    }

    public function initDataBase(): void{
        self::getSQLite()->exec(query: "CREATE TABLE IF NOT EXISTS `users` (`id` INTEGER PRIMARY KEY, `username` TEXT, `languager` TEXT)");
    }

    public function isUser(string $username): bool{
        $query = self::getSQLite()->prepare("SELECT * FROM `users` WHERE `username`=:username");
        $query->bindValue(":username", $username, SQLITE3_TEXT);
        $result = $query->execute();
        if(!$result->fetchArray(SQLITE3_ASSOC)){
            return true;
       }
        return false;
    }

    public function setUser(string $username, string $languager): void{
        $query = self::getSQLite()->prepare("INSERT INTO `users` (`username`, `languager`) VALUES (:username, :languager);");
        $query->bindValue(":username", $username, SQLITE3_TEXT);
        $query->bindValue(":languager", $languager, SQLITE3_TEXT);
        $query->execute();
        \GlobalLogger::get()->info("New user: ".$username." | languager: ".$languager);
    }

    public function getLanguager(string $username): string{
        $result = self::getSQLite()->query("SELECT `languager` FROM `users` WHERE `username`='$username'");;
        while($row = $result->fetchArray(SQLITE3_ASSOC)){
            return $row["languager"];
        }
        return 'null';
    }

    public function updateLanguager(string $username, string $languager): void{
        $query = self::getSQLite()->prepare("UPDATE `users` SET `languager`=:languager WHERE `username`=:username");
        $query->bindValue(":languager", $languager, SQLITE3_TEXT);
        $query->bindValue(":username", $username, SQLITE3_TEXT);
        $query->execute();
    }

    public function removeUser(string $username): void{
        $query = self::getSQLite()->prepare("DELETE FROM `users` WHERE `username`=:username");
        $query->bindValue(":username", $username, SQLITE3_TEXT);
        $query->execute();
    }

    private static function getSQLite(): \SQLite3{
        return self::$database;
    }
}