# MuteAPI - V1.0.0
**A simple API to mute players!**

---------------------------------

### Poggit Virion
[Check the MuteAPI Virion out](https://poggit.pmmp.io/ci/supercrafter333/MuteAPI/~)

### How2Use?
**Mute a player**:
- Time Muting Example:
```php
<?php

namespace supercrafter333\TestPlugin;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use supercrafter333\MuteAPI\MuteAPI;

class TestPlugin extends PluginBase
{
    
    /*Other stuff*/
    
    public function onCommand(CommandSender $sender,Command $command,string $label,array $args) : bool
    {
        if ($command == "mute") {
            if (!$sender instanceof Player) {
                $sender->sendMessage("Only In-Game!");
                return true;
            }
            if (count($args) >= 3) {
                $player = $this->getServer()->getPlayer($args[0]);
                if ($player === null) {
                    $sender->sendMessage("§cPlayer is not online!");
                    return true;
                }
                $reason = $args[1];
                $time = $args[2];
                $muteApi = new MuteAPI($this);
                $muteMgr = $muteApi->getMuteMgr("§cYou're Muted! You can't send a message, when you're muted!");
                $playerData = $muteMgr->getPlayerData($player);
                if (!$time instanceof \DateInterval) {
                    $sender->sendMessage("§cPlease use the §7DateInterval§c format!");
                    return true;
                }
                $playerData->setTimeMuted(true, $player, $reason, $time);
                $sender->sendMessage("§aYou've successfully muted the player §7" . $player->getName() . "§a!");
                $player->sendMessage("§aYou're now muted by §7" . $sender->getName() . "§a!");
            }
        }
    }
}
```
- Simple Muting Example:
```php
<?php

namespace supercrafter333\TestPlugin;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use supercrafter333\MuteAPI\MuteAPI;

class TestPlugin extends PluginBase
{
    
    /*Other stuff*/
    
    public function onCommand(CommandSender $sender,Command $command,string $label,array $args) : bool
    {
        if ($command == "mute") {
            if (!$sender instanceof Player) {
                $sender->sendMessage("Only In-Game!");
                return true;
            }
            if (count($args) >= 2) {
                $player = $this->getServer()->getPlayer($args[0]);
                if ($player === null) {
                    $sender->sendMessage("§cPlayer is not online!");
                    return true;
                }
                $reason = $args[1];
                $muteApi = new MuteAPI($this);
                $muteMgr = $muteApi->getMuteMgr("§cYou're Muted! You can't send a message, when you're muted!");
                $playerData = $muteMgr->getPlayerData($player);
                $playerData->setMuted(true, $reason);
                $sender->sendMessage("§aYou've successfully muted the player §7" . $player->getName() . "§a!");
                $player->sendMessage("§aYou're now muted by §7" . $sender->getName() . "§a!");
            }
        }
    }
}
```
- Is Muted Example:
```php
public function onChat(\pocketmine\event\player\PlayerChatEvent $event)
{
    $player = $event->getPlayer();
    $muteApi = new MuteAPI($this);
    $muteMgr = $muteApi->getMuteMgr("§cYou're Muted! You can't send a message, when you're muted!");
    $playerData = $muteMgr->getPlayerData($player);
    if ($playerData->isMuted()) {
        $player->sendMessage($muteMgr->getMuteMessage());
        $event->setCancelled(true);
    }
}
```

### License
MuteAPI is Licensed under the [Apache 2.0 License](/LICENSE) by supercrafter333!
