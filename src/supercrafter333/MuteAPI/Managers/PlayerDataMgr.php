<?php

namespace supercrafter333\MuteAPI\Managers;

use DateTime;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;

/**
 * Class PlayerDataMgr
 * @package supercrafter333\MuteAPI\Managers
 */
class PlayerDataMgr
{

    /**
     * @var Player
     */
    protected $player;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var Plugin
     */
    protected $plugin;

    /**
     * PlayerDataMgr constructor.
     * @param Player $player
     * @param Plugin $plugin
     */
    public function __construct(Player $player, Plugin $plugin)
    {
        $this->plugin = $plugin;
        $this->player = $player;
        $this->name = $player->getName();
    }

    /**
     * @return Config
     */
    protected function getMuteList(): Config
    {
        $cfg = new Config($this->plugin->getDataFolder() . "mutelist.yml", Config::YAML);
        return $cfg;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return string
     */
    public function getPlayerName(): string
    {
        return $this->name;
    }

    /**
     * Refresh the MuteList. This function will save and reload the list.
     */
    public function refreshList()
    {
        $this->getMuteList()->save();
        $this->getMuteList()->reload();
    }

    /**
     * Check if a player is muted.
     *
     * @return bool
     */
    public function isMuted(): bool
    {
        $name = $this->name;
        if ($this->getMuteList()->exists($name) && $this->getMuteList()->get($name)["muted"] == true) {
            return true;
        }
        return false;
    }

    /**
     * Un mute a player.
     *
     * @return bool
     */
    public function unMute(): bool
    {
        if ($this->getMuteList()->exists($this->name)) {
            $this->getMuteList()->remove($this->name);
            return true;
        }
        return false;
    }

    /**
     * Check if a player is muted for some time.
     *
     * @return bool
     * @throws \Exception
     */
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

    /**
     * Set a player un-/muted
     *
     * @param bool $muted
     * @param string $reason
     */
    public function setMuted(bool $muted = true, $reason = "")
    {
        $name = $this->name;
        if ($muted == true) {
            if ($reason !== "") {
                $this->getMuteList()->set($name, ["muted" => true, "reason" => $reason]);
                $this->refreshList();
            } else {
                $this->getMuteList()->set($name, ["muted" => true]);
                $this->refreshList();
            }
        } else {
            if ($this->isMuted()) {
                $this->getMuteList()->remove($name);
                $this->refreshList();
            }
        }
    }

    /**
     * Get the date of the mute.
     *
     * @return DateTime|false
     * @throws \Exception
     */
    public function getMuteDate()
    {
        if ($this->isTimeMuted()) {
            return new DateTime($this->getMuteList()->get($this->name)["date"]);
        }
        return false;
    }

    /**
     * Get the reason of the mute.
     *
     * @return false|mixed
     */
    public function getMuteReason()
    {
        if ($this->isMuted() && $this->getMuteList()->exists($this->name)["reason"]) {
            return $this->getMuteList()->get($this->name)["reason"];
        }
        return false;
    }

    /**
     * Set a player time un-/muted
     *
     * @param bool $muted
     * @param string $reason
     * @param \DateInterval $muteDate
     * @throws \Exception
     */
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
                $this->refreshList();
            } else {
                $setThis = ["muted" => true, "reason" => $reason, "date" => $date->format("Y.m.d H:i:s")];
                $this->getMuteList()->set($this->name, $setThis);
                $this->refreshList();
            }
        } else {
            $this->unMute();
        }
    }
}