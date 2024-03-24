<?php

declare(strict_types=1);

namespace Khenford;

use Khenford\command\LanguageCommand;
use Khenford\event\PlayerJoin;
use pocketmine\plugin\PluginBase;
use Khenford\database\SQLite;
use pocketmine\utils\Config;
class Languager extends PluginBase{

    private static ?SQLite $database = null;
    private static ?Languager $instance = null;

    public function onEnable(): void{
        $this->getServer()->getPluginManager()->registerEvents(new PlayerJoin(), $this);
        $this->getServer()->getCommandMap()->register("", new LanguageCommand());
        self::$database = new SQLite($this, "user.db");
        $this->loadConfig();
        self::$instance = $this;
        //$fileList = array_diff( scandir(Languager::getInstance()->getDataFolder()."translations"), array( '..', '.' ) );
        //var_dump($fileList);
    }

    public function loadConfig(): void{
        if(!is_dir($this->getDataFolder()."translations")){
            @mkdir($this->getDataFolder()."translations");
        }
        $this->saveDefaultConfig();
        foreach($this->getResources() as $path) {
            $path = (string)$path;
            $dirname = basename(dirname($path));
            if($dirname == "translations") {
                $filename = basename($path);
                if($this->saveResource($dirname."/".$filename, false)) {
                    \GlobalLogger::get()->info("Translation $filename copied");
                }
            }
        }
    }

    public function getFilesAll(): array{
        $list=array();
        $scan=array_diff(scandir($this->getDataFolder()."/translations"), array('..', '.'));
        foreach($scan as $dir) {
            if(is_dir($dir))$list[$dir]=scandir($dir);
            else $list[]=$dir;
        }
        $filename = array();
        foreach ($list as $filesname){
            $filename[] = pathinfo($filesname, PATHINFO_FILENAME);
        }
        return $filename;
    }

    public static function Translation(string $username, string $query): string|bool|array{
        $config = new Config(self::getInstance()->getDataFolder()."translations/".self::getDataBase()->getLanguager($username).".yml", Config::YAML);
        return self::getProperty($config, $query);
    }
    public static function getProperty(Config $config, string $query) : string|int|bool {
        $keys = explode(".", $query);
        if (count($keys) == 1 && $keys[0] == $query) {
            return $config->get($keys[0]);
        } else {
            $data = [];
            foreach ($keys as $key) {
                if (empty($data)) {
                    $data = $config->get($key);
                } else {
                    $data = $data[$key];
                }
            }
        }
        return $data;
    }

    public static function getInstance(): ?Languager{
        return self::$instance;
    }

    public static function getDataBase(): SQLite | null{
        return self::$database;
    }
}
