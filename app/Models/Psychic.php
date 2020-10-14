<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * [Description Psychic]
 * Psychic model
 */
class Psychic extends Model
{
    protected $fillable = ['serial_number','hypothesis','score','succes','fails'];

    protected $appends = ['trys','person'];

    public function setTrysAttribute(array $trys): void
    {
        $this->attributes['trys'] = $trys;
    }

    public function getTrysAttribute(): array
    {
        return isset($this->attributes['trys']) ? $this->attributes['trys'] : [];
    }

    public function setPersonAttribute(Person $person): void
    {
        $this->attributes['person'] = $person;
    }

    public function getPersonAttribute(): Person
    {
        return $this->attributes['person'];
    }

    /**
     * Default model
     * @param int $serialNomber serial number of psychic
     *
     * @return Psychic model
     */
    public static function default(int $serialNomber = 0): Psychic
    {
        $psychic = new self();

        $psychic->session_id = session()->getId();

        $psychic->serial_number = $serialNomber;

        $psychic->hypothesis = 0;

        $psychic->score = 0;

        $psychic->succes = 0;

        $psychic->fails = 0;

        $psychic->trys = array();

        return $psychic;
    }

    /**
     * From array to model
     * @param array $item array of model attributes
     * @return Psychic model
     */
    public static function takeFromArray(array $item): Psychic
    {
        $psychic = new self($item);
        $psychic->person = new Person($item['person']);
        $trys = array();
        foreach ($item['trys'] as $try) {
            $trys[] = new PsychicTry($try);
        }
        $psychic->trys = $trys;

        return $psychic;
    }

    /**
     * Results to string
     * @return string results
     */
    public function resultsToString(): string
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
