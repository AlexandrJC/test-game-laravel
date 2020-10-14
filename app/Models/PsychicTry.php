<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * [Description PsychicTry]
 * PsychicTry model
 */
class PsychicTry extends Model
{
    protected $fillable = ['session_id','serial_number','nomber'];

    /**
     * Default model
     * @param int $serialNomber serial number of psychic
     * @param int $nomber current nomber
     * @return PsychicTry
     */
    public static function default(int $serialNomber = 0, int $nomber = 0): PsychicTry
    {
        $try = new self();
        $try->session_id = session()->getId();
        $try->serial_number = $serialNomber;
        $try->nomber = $nomber;

        return $try;
    }

    /**
     * Model to string
     * @return string
     */
    public function toString(): string
    {
        return $this->nomber;
    }
}
