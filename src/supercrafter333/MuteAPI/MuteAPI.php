<?php

namespace supercrafter333\MuteAPI;

use pocketmine\plugin\Plugin;
use supercrafter333\MuteAPI\Managers\MuteMgr;

/**
 * Class MuteAPI
 * @package supercrafter333\MuteAPI
 */
class MuteAPI
{

    //protected static $instance;
    /**
     * @var Plugin
     */
    protected static $plugin;

    /**
     * MuteAPI constructor.
     * @param Plugin $plugin
     */
    public function __construct(Plugin $plugin)
    {
        self::$plugin = $plugin;
    }

    /**
     * @return Plugin
     */
    public function getPlugin(): Plugin
    {
        return self::$plugin;
    }

    /**
     * @param string $muteMessage
     * @return MuteMgr
     */
    public function getMuteMgr(string $muteMessage = "§cYou're Muted!"): MuteMgr
    {
        return new MuteMgr($muteMessage, $this->getPlugin());
    }
}