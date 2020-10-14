<?php

namespace App\Services;

use App\Models\Player;
use App\Models\PlayerTry;
use App\Repositories\IAppStorageInterface;
use Illuminate\Support\Facades\Config;

/**
 * [Description PlayerService]
 * Service for realization
 */
class PlayerService
{
    /**
     * Interface for data storage management
     * @var IAppStorageInterface
     */
    private IAppStorageInterface $storage;

    /**
     * Player model for game
     * @var Player
     */
    private Player $playermodel;

    /**
     * Create a new service instance for player with dependencies enjection of IAppStorageInterface and Player model
     * @param IAppStorageInterface $storage Interface for data storage management
     * @param Player $playeMmodel Player model for game
     */
    public function __construct(IAppStorageInterface $storage, Player $playeMmodel)
    {
        $this->playermodel = $playeMmodel;
        $this->storage = $storage;
    }

    /**
     * Load player data from storage
     * @param IAppStorageInterface $storage Interface for data storage management
     * @return Player Player model for game
     */
    public static function loadPlayer(IAppStorageInterface $storage): Player
    {
        $key = Config::get('game.keyUser');

        $playerdata = $storage->loadData($key);

        $player = Player::default();

        if ($playerdata == null) {
            $storage->saveData($key, [$player]);
            return $player;
        }

        foreach ($playerdata as $item) {
            if ($item instanceof Player) {
                return $item;
            } else {
                return Player::takeFromArray($item);
            }
        }
        return $player;
    }

    /**
     * Update player data in storage
     * @param int $nomber current nomber in try
     * @return void
     */
    public function updatePlayerData(int $nomber): void
    {
        $key = Config::get('game.keyUser');

        $try = PlayerTry::default($nomber);

        $this->playermodel->trys = array_merge($this->playermodel->trys, [$try]);

        $this->storage->saveData($key, [$this->playermodel]);
    }
}
