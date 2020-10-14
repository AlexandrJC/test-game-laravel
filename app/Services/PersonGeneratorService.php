<?php

namespace App\Services;

use App\Models\Person;

/**
 * [Description PersonGeneratorService]
 * Service for generating a person for a psychic
 */
class PersonGeneratorService
{
     /**
     * Pictures for person
     * @var array
     */
    private static $pics = [
        '😀','😁','😂','🤣','😃','😄','😅','😆','😊','😋','😎','😍','😘','😗','😙','😚',
        '🙂','🤗','🤩','🤔','🤨','😐','😑','😶','🙄','😏','😣','😥','😮','🤐','😯','😪',
        '😫','😴','😌','😛','😜','🤤','😒','😓','😔','😕','🙃','🤑','😲','🙁','😖','😞',
        '😟','😤','😢','😭','😦','😧','😨','😩','🤯','😬','😰','😱','😳','🤪','😵','😡',
        '😠','🤬','😷','🤒','🤕','🤢','🤮','🤧'
        ];

    /**
     * Titles for person
     * @var array
     */
    private static $titles = ['Ведьма', 'Колдун'];

    /**
     * Genders for person
     * @var array
     */
    private static $genders = ['female', 'male'];

    /**
     * Generate random person data
     * @return Person Person Model
     */
    public static function getPerson(int $serialNomber): Person
    {
        $genderIndex = rand(0, 1);

        $faker = \Faker\Factory::create('ru_RU'); // create a Russian faker

        $person = new Person();

        $person->age = rand(20, 120);
        $person->title = self::$titles[$genderIndex];
        $person->fio = $faker->name(self::$genders[$genderIndex]);
        $person->picture = self::getPicture();
        $person->session_id = session()->getId();
        $person->serial_number = $serialNomber;


        return $person;
    }

    /**
     * Get random picture
     * @return string
     */
    private static function getPicture(): string
    {
        return self::$pics[rand(0, count(self::$pics) - 1)];
    }
}
