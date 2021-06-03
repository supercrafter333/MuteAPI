<?php

namespace supercrafter333\MuteAPI\Managers;

use pocketmine\Player;
use pocketmine\plugin\Plugin;

/**
 * Class MuteMgr
 * @package supercrafter333\MuteAPI\Managers
 */
class MuteMgr
{

    /**
     * @var string
     */
    public static $muteMessage;
    /**
     * @var Plugin
     */
    protected $plugin;

    /**
     * MuteMgr constructor.
     * @param Plugin $plugin
     * @param string $muteMessage
     */
    public function __construct(Plugin $plugin, string $muteMessage = "§cYou're Muted!")
    {
        $this->plugin = $plugin;
        self::$muteMessage = $muteMessage;
    }

    /**
     * @param Player $player
     * @return PlayerDataMgr
     */
    public function getPlayerData(Player $player)
    {
        return new PlayerDataMgr($player, $this->plugin);
    }

    /**
     * @return string
     */
    public function getMuteMessage(): string
    {
        return self::$muteMessage;
    }
}