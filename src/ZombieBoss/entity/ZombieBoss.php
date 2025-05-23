<?php

declare(strict_types=1);

namespace ZombieBoss\entity;

use pocketmine\entity\Zombie;
use pocketmine\entity\Location;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\entity\Attribute;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\effect\VanillaEffects;

class ZombieBoss extends Zombie{

    public static function getNetworkTypeId() : string{
        return 'zombieboss:zombie_boss';
    }

    protected function getInitialSizeInfo() : EntitySizeInfo{
        return new EntitySizeInfo(2.0, 2.0); // bigger than normal zombie
    }

    public function initEntity(CompoundTag $nbt) : void{
        parent::initEntity($nbt);
        $this->setMaxHealth(1000);
        $this->setHealth(1000);
        $this->setScale(2.0);
        $this->getAttributeMap()->get(Attribute::MOVEMENT_SPEED)->setValue(0.4);
        $this->getAttributeMap()->get(Attribute::ATTACK_DAMAGE)->setValue(20.0);
    }
}
