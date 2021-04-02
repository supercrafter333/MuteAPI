<?php

namespace supercrafter333\MuteAPI;

use pocketmine\plugin\Plugin;
use supercrafter333\MuteAPI\Managers\MuteMgr;

class MuteAPI
{

    //protected static $instance;
    protected static $plugin;

    public function __construct(Plugin $plugin)
    {
        self::$plugin = $plugin;
    }

    public function getPlugin(): Plugin
    {
        return self::$plugin;
    }

    public function getMuteMgr(string $muteMessage = "§cYou're Muted!"): MuteMgr
    {
        return new MuteMgr($muteMessage, $this->getPlugin());
    }
}