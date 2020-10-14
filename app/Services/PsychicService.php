<?php

namespace App\Services;

use App\Models\Person;
use App\Models\Psychic;
use App\Models\PsychicTry;
use App\Services\PsychicTryService;

/**
 * [Description PsychicService]
 * PsychicService is a service class for Psychic model to realize the game logic
 */
class PsychicService
{
    /**
     * Psychic model
     * @var Psychic
     */
    private $psychicModel;

    /**
     * PsychicService constructor based on Psychic models
     * @param Psychic $psychicModel
     */
    public function __construct(Psychic $psychicModel)
    {
        $this->psychicModel = $psychicModel;
    }

    /**
     * Make a job for Psychic model
     * @return void
     */
    public function makeJob(): void
    {
        $this->psychicModel->hypothesis = rand(10, 99);
        $try = PsychicTry::default($this->psychicModel->serial_number, $this->psychicModel->hypothesis);
        $trys = array_merge($this->psychicModel->trys, [$try]);
        $this->psychicModel->trys = $trys;
    }

    /**
     * Clear the job for Psychic model
     * @return void
     */
    public function clearJob(): void
    {
        $this->psychicModel->hypothesis = 0;
    }

    /**
     * Check the result of Psychic model
     * @param int $no Current number
     *
     * @return bool
     */
    public function checkResult(int $no): bool
    {
        if ($this->psychicModel->hypothesis == $no) {
            $this->psychicModel->score++;
            $this->psychicModel->succes++;
            return true;
        }

        $this->psychicModel->score--;
        $this->psychicModel->fails++;
        return false;
    }
}
