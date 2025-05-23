<?php

declare(strict_types=1);

namespace ZombieBoss\entity;

use pocketmine\entity\Zombie;
use pocketmine\entity\Zombie as VanillaZombie;
use pocketmine\entity\Location;
use pocketmine\player\Player;
use pocketmine\entity\effect\{EffectInstance, VanillaEffects};
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\entity\Attribute;
use pocketmine\entity\EntitySizeInfo;

class ZombieBoss extends Zombie{

    /** @var int ticks */
    protected int $comboCooldown = 0;

    protected ?Player $target = null;

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

    protected function findTarget() : ?Player{
        foreach($this->getWorld()->getNearbyEntities($this->boundingBox->expandedCopy(16, 8, 16), $this) as $e){
            if($e instanceof Player && !$e->isSpectator()){
                return $e;
            }
        }
        return null;
    }

    protected function performCombo() : void{
        if($this->target === null){
            return;
        }
        switch(random_int(1, 3)){
            case 1:
                $this->leapAtTarget();
                break;
            case 2:
                $this->summonMinions();
                break;
            case 3:
                $this->enrage();
                break;
        }
    }

    protected function leapAtTarget() : void{
        $dir = $this->target->getPosition()->subtractVector($this->getPosition())->normalize()->multiply(1.5);
        $dir->y = 0.8;
        $this->setMotion($dir);
    }

    protected function summonMinions() : void{
        $world = $this->getWorld();
        for($i = 0; $i < 3; $i++){
            $loc = $this->getLocation()->add(mt_rand(-3, 3), 0, mt_rand(-3, 3));
            $z = new VanillaZombie(Location::fromObject($loc, $world, $this->yaw, 0));
            $z->spawnToAll();
        }
    }

    protected function enrage() : void{
        $effect = new EffectInstance(VanillaEffects::STRENGTH(), 200, 1, false);
        $this->getEffects()->add($effect);
    }

    public function entityBaseTick(int $tickDiff = 1) : bool{
        $changed = parent::entityBaseTick($tickDiff);

        if($this->comboCooldown > 0){
            $this->comboCooldown -= $tickDiff;
        }

        if($this->target === null || !$this->target->isAlive() || $this->target->isClosed() || $this->target->getWorld() !== $this->getWorld()){
            $this->target = $this->findTarget();
        }

        if($this->comboCooldown <= 0 && $this->target !== null){
            $this->performCombo();
            $this->comboCooldown = 200; // 10 seconds
        }

        return $changed;
    }
}
