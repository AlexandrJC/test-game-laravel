<?php

namespace App\Factories;

use Illuminate\Support\Facades\Config;
use App\Repositories\IAppStorageInterface;
use App\Services\PsychicService;
use App\Services\PersonGeneratorService;
use App\Models\Psychic;

/**
 * [Description PsychicsFactory]
 * Fabric for generate new psychics and realization of game logic for each psychic
  */
class PsychicsFactory
{
    /**
     * Array of psychics
     * @var array
     */
    private array $Psychics = [];

    /**
     * Min amount of psychics
     * @var int
     */
    private int $Min = 0;

    /**
     * Max amount of psychics
     * @var int
     */
    private int $Max = 0;

    /**
     * Key for storage
     * @var string
     */
    private string $Key = "";

    /**
     * Interface for data storage management
     * @var IAppStorageInterface
     */
    private IAppStorageInterface $storage;

    /**
     * Create a new fabric instance with dependencies enjection of IAppStorageInterface
     * @param IAppStorageInterface $storage
     */
    public function __construct(IAppStorageInterface $storage)
    {
        $this->storage = $storage;

        $this->Min = Config::get('game.min');

        $this->Max = Config::get('game.max');

        $this->Key = Config::get('game.keyPsychics');

        if ($this->storage->loadData($this->Key) == null) {
            $this->generatePsychics();
        } else {
            $this->loadPsychics();
        }
    }

    /**
     * Generate new psychics and save to storage
     * @return [type]
     */
    public function generatePsychics()
    {
        $amount = rand($this->Min, $this->Max);

        for ($i = 0; $i < $amount; $i++) {
            $this->Psychics[$i] = Psychic::default($i);

            $this->Psychics[$i]->person = PersonGeneratorService::getPerson($i);
        }

        $this->savePsychicsData();

        return $this->Psychics;
    }

    /**
     * Get all psychics
     * @return array
     */
    public function takeAll()
    {
        return $this->Psychics;
    }

    /**
     * Save psychics to storage
     * @return void
     */
    private function savePsychicsData()
    {
        $this->storage->saveData($this->Key, $this->Psychics);
    }

    /**
     * Load psychics from storage
     * @return [type]
     */
    public function loadPsychics()
    {
        $modelArray = $this->storage->loadData($this->Key);
        foreach ($modelArray as $item) {
            if ($item instanceof Psychic) {
                array_push($this->Psychics, $item);
            } else {
                array_push($this->Psychics, Psychic::takeFromArray($item));
            }
        }
        $this->savePsychicsData();
        return $this->Psychics;
    }

    /**
     * Make job for all psychics realization of game logic
     * @return [type]
     */
    public function allMakeJob(): void
    {
        foreach ($this->Psychics as $item) {
            $service = new PsychicService($item);
            $service->makeJob();
        }

        $this->savePsychicsData();
    }

    /**
     * Clear jobs for all psychics realization of game logic
     * @return void
     */
    public function allClearJob(): void
    {
        foreach ($this->Psychics as $item) {
            $service = new PsychicService($item);
            $service->clearJob();
        }

        $this->savePsychicsData();
    }

    /**
     * Check result of jobs for all psychics and return winners psychics
     * @param int $no - current number
     * @return array - array of winners psychics
     */
    public function allCheckResult(int $no): array
    {
        $best = [];

        foreach ($this->Psychics as $item) {
            $service = new PsychicService($item);

            if ($service->checkResult($no)) {
                array_push($best, $item);
            }
        }

        $this->savePsychicsData();

        return $best;
    }
}
