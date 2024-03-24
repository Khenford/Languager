<?php

namespace Khenford\forms;

use com\formconstructor\form\CustomForm;
use com\formconstructor\form\element\custom\Dropdown;
use com\formconstructor\form\response\CustomFormResponse;
use pocketmine\lang\Language;
use pocketmine\player\Player;
use Khenford\Languager;
class Change{
    public function __construct(Player $player){
        $form = new CustomForm(Languager::Translation($player->getDisplayName(), "language.title"));
        $form->addContent(Languager::Translation($player->getDisplayName(), "language.content"));
        $fileList = Languager::getInstance()->getFilesAll();
        $dropdowns = (new Dropdown("Dropdown"));
        foreach ($fileList as $list) {
            $dropdowns->addText($list);
        }
        $form->addElement("dropdown", $dropdowns);

        $form->setHandler(function (Player $player, CustomFormResponse $response) {
            $dropdown = $response->getDropdown("dropdown")->getValue()->getName();
            if(Languager::getDataBase()->getLanguager($player->getDisplayName()) == $dropdown){
                $player->sendMessage(Languager::Translation($player->getDisplayName(), "language.error"));
                return;
            }
            Languager::getDataBase()->updateLanguager($player->getDisplayName(), $dropdown);
            $player->sendMessage(Languager::Translation($player->getDisplayName(), "language.success"));
        });
        $form->send($player);
    }
}