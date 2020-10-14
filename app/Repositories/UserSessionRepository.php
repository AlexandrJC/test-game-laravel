<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Session;
use App\Repositories\IAppStorageInterface;

/**
 * [Description UserSessionRepository]
 * Class UserSessionRepository for storage data in session
 */
class UserSessionRepository implements IAppStorageInterface
{
    /**
     * Load game data from session storage
     * @param string $key
     *
     * @return array|null
     */
    public function loadData(string $key): ?array
    {
        $session = Session::get($key);

        if ($session == null) {
            return null;
        }
        $data = json_decode($session, true);
        return $data;
    }

    /**
     * Save game data to session storage
     * @param string $key
     * @param array $modelsArray
     *
     * @return [type]
     */
    public function saveData(string $key, array $modelsArray)
    {
        $json = json_encode($modelsArray);
        Session::put($key, $json);
    }


    /**
     * Clear game data in session storage
     * @return void
     */
    public function clearData(): void
    {
        Session::flush();
    }
}
