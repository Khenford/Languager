<?php

namespace Khenford\event;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use Khenford\Languager;

class PlayerJoin implements Listener{

    public function onJoin(PlayerJoinEvent $event): void{
        if(Languager::getDataBase()->isUser($event->getPlayer()->getDisplayName())){
            foreach (Languager::getInstance()->getFilesAll() as $list) {
                if($list !== $event->getPlayer()->getPlayerInfo()->getLocale()){
                    Languager::getDataBase()->setUser($event->getPlayer()->getDisplayName(), Languager::getInstance()->getConfig()->get("default"));
                }else{
                    Languager::getDataBase()->setUser($event->getPlayer()->getDisplayName(), $event->getPlayer()->getPlayerInfo()->getLocale());
                }
            }
        }
    }
}