# ZombieBoss

Spawn an overpowered zombie boss in PocketMine-MP. Use `/spawnboss` in-game to summon it.

The boss has high health and custom battle AI with combo moves, including leaping attacks,
summoning minions, and temporary strength boosts. Challenge your players with a tough fight!

## Configuration

ZombieBoss reads `config.yml` for all of its stats. After first start a default configuration
is generated in the plugin data folder:

```yaml
health: 1000
movement-speed: 0.4
attack-damage: 20.0
combo-cooldown: 200
minion-count: 3
```

Edit these values to tune the boss difficulty. Run `/zbreload` to reload the configuration
without restarting your server.
