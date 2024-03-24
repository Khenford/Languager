<?php

namespace Khenford\command;

use Khenford\forms\Change;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class LanguageCommand extends Command{

    public function __construct(){
        parent::__construct("language", "change the server translations");
        $this->setPermission("language.command");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void{
        if($sender instanceof Player){
            new Change($sender);
        }
    }
}
