<?php

namespace supercrafter333\MuteAPI\Managers;

use pocketmine\Player;
use pocketmine\plugin\Plugin;

class MuteMgr
{

    public static $muteMessage;
    protected $plugin;

    public function __construct(Plugin $plugin, string $muteMessage = "§cYou're Muted!")
    {
        $this->plugin = $plugin;
        self::$muteMessage = $muteMessage;
    }

    public function getPlayerData(Player $player)
    {
        return new PlayerDataMgr($player, $this->plugin);
    }

    public function getMuteMessage(): string
    {
        return self::$muteMessage;
    }
}