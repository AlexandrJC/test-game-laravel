<?php

namespace App\Repositories;

/**
 * [Description IAppStorageInterface]
 * Interface IAppStorageInterface for storage data in session or database
 */
interface IAppStorageInterface
{
    /**
     * Load game data from storage
     * @param string $key
     * @return array|null
     */
    public function loadData(string $key): ?array;

    /**
     * Save game data to storage
     * @param string $key
     * @param array $modelsArray
     *
     * @return [type]
     */
    public function saveData(string $key, array $modelsArray);

    /**
     * Clear game data in storage
     * @return void
     */
    public function clearData(): void;
}
