<?php

namespace supercrafter333\MuteAPI\Managers;

use DateTime;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;
use supercrafter333\MuteAPI\MuteAPI;

class PlayerDataMgr
{

    protected $player;
    protected $name;
    protected $plugin;

    public function __construct(Player $player, Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->player = $player;
        $this->name = $player->getName();
    }

    protected function getMuteList(): Config
    {
        $cfg = new Config($this->plugin->getDataFolder() . "mutelist.yml", Config::YAML);
        return $cfg;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getPlayerName(): string
    {
        return $this->name;
    }

    public function isMuted(): bool
    {
        $name = $this->name;
        if ($this->getMuteList()->exists($name) && $this->getMuteList()->get($name)["muted"] == true) {
            return true;
        }
        return false;
    }

    public function unMute(): bool
    {
        if ($this->getMuteList()->exists($this->name)) {
            $this->getMuteList()->remove($this->name);
            return true;
        }
        return false;
    }

    public function isTimeMuted(): bool
    {
        if ($this->isMuted()) {
            if ($this->getMuteList()->exists($this->name)["date"]) {
                $now = new DateTime('now');
                $cfgDate = $this->getMuteList()->get($this->name)["date"];
                $date = new DateTime($cfgDate);
                if ($now >= $date) {
                    $this->unMute();
                    return false;
                } else {
                    return true;
                }
            }
        }
        return false;
    }

    public function setMuted(bool $muted = true, $reason = "")
    {
        $name = $this->name;
        if ($muted == true) {
            if ($reason !== "") {
                $this->getMuteList()->set($name, ["muted" => true, "reason" => $reason]);
                $this->getMuteList()->save();
            } else {
                $this->getMuteList()->set($name, ["muted" => true]);
                $this->getMuteList()->save();
            }
        } else {
            if ($this->isMuted()) {
                $this->getMuteList()->remove($name);
                $this->getMuteList()->save();
            }
        }
    }

    public function getMuteDate()
    {
        if ($this->isTimeMuted()) {
            return new DateTime($this->getMuteList()->get($this->name)["date"]);
        }
        return false;
    }

    public function getMuteReason()
    {
        if ($this->isMuted() && $this->getMuteList()->exists($this->name)["reason"]) {
            return $this->getMuteList()->get($this->name)["reason"];
        }
        return false;
    }

    public function setTimeMuted(bool $muted, string $reason, \DateInterval $muteDate)
    {
        if ($muted == true) {
            $now = new DateTime('now');
            $date = $now->add($muteDate);
            if ($this->isTimeMuted()) {
                $dateBefore = $this->getMuteDate();
                $newDate = $dateBefore->add($muteDate);
                $setThis = ["muted" => true, "reason" => $reason, "date" => $newDate->format("Y.m.d H:i:s")];
                $this->getMuteList()->set($this->name, $setThis);
                $this->getMuteList()->save();
            } else {
                $setThis = ["muted" => true, "reason" => $reason, "date" => $date->format("Y.m.d H:i:s")];
                $this->getMuteList()->set($this->name, $setThis);
                $this->getMuteList()->save();
            }
        } else {
            $this->unMute();
        }
    }
}