<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\ILocalStorrage;
use Illuminate\Support\Facades\Config;
use JsonSerializable;

/**
 * [Description Player]
 * Player model
 */
class Player extends Model implements JsonSerializable
{
    protected $fillable = ['user_session_id'];

    protected $appends = ['trys'];
    public function setTrysAttribute(array $trys): void
    {
        $this->attributes['trys'] = $trys;
    }

    public function getTrysAttribute(): array
    {
        return isset($this->attributes['trys']) ? $this->attributes['trys'] : [];
    }

    /**
     * From array to model
     * @param array $item array of model attributes
     * @return Player model
     */
    public static function takeFromArray(array $item): Player
    {
        $player = new self($item);
        $trys = array();
        foreach ($item['trys'] as $try) {
            $trys[] = new PlayerTry($try);
        }
        $player->trys = $trys;

        return $player;
    }

    /**
     * Default model
     * @return Player
     */
    public static function default(): Player
    {
        $player = new self();

        $player->trys = [];

        return $player;
    }

    /**
     * To string requests
     * @return string
     */
    public function requestsToString(): string
    {
        $val = '';

        foreach ($this->trys as $try) {
            $val .= $try->toString() . ',';
        }

        return substr($val, 0, -1);
    }

    /**
     * Count trys
     * @return int
     */
    public function takeTrys(): int
    {
        return count($this->trys);
    }
}
