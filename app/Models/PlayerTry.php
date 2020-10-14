<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * [Description PlayerTry]
 * PlayerTry model
 */
class PlayerTry extends Model
{
    protected $fillable = ['session_id','nomber'];

    /**
     * Default model
     * @param int $nomber current nomber
     * @return PlayerTry model
     */
    public static function default(int $nomber = 0): PlayerTry
    {
        $try = new self();
        $try->session_id = session()->getId();
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
