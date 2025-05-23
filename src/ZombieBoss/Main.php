<?php

declare(strict_types=1);

namespace ZombieBoss;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\world\World;
use pocketmine\nbt\tag\CompoundTag;
use ZombieBoss\entity\ZombieBoss as BossEntity;

class Main extends PluginBase{

    protected function onEnable(): void{
        EntityFactory::getInstance()->register(BossEntity::class, function(World $world, CompoundTag $nbt): BossEntity{ return new BossEntity(EntityDataHelper::parseLocation($nbt, $world), $nbt); }, ["ZombieBoss"]);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        if($command->getName() === 'spawnboss'){
            if(!$sender instanceof Player){
                $sender->sendMessage('Run this command in-game.');
                return true;
            }
            $boss = new BossEntity($sender->getLocation());
            $boss->spawnToAll();
            $sender->sendMessage('The Zombie Boss has been summoned!');
            return true;
        }
        return false;
    }
}
