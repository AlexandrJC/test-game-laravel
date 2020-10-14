<?php

namespace App\Http\Controllers;

use App\Repositories\IAppStorageInterface;
use App\Services\PlayerService;
use Illuminate\Http\Request;
use App\Factories\PsychicsFactory;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Illuminate\Routing\Redirector;
use App\Models\Player;

/**
 * [Description GameController]
 * Main controller for game logic
 */
class GameController extends Controller
{
    /**
     * Factory for psychics management and generation
     * @var PsychicsFactory
     */
    private PsychicsFactory $PsychicsFactory;

    /**
     * Player model for game
     * @var Player
     */
    private Player $Player;

    /**
     * Player service for game logic
     * @var PlayerService
     */
    private PlayerService $PlayerService;

    /**
     * Interface for data storage management
     * @var IAppStorageInterface
     */
    private IAppStorageInterface $appstorage;

    /**
     * Create a new controller instance with dependencies enjection of IAppStorageInterface
     * @param IAppStorageInterface $appStorage Interface for data storage management
     */
    public function __construct(IAppStorageInterface $appStorage)
    {
        $this->appstorage = $appStorage;
        $this->Player = PlayerService::loadPlayer($this->appstorage);
        $this->PlayerService = new PlayerService($this->appstorage, $this->Player);
        $this->PsychicsFactory = new PsychicsFactory($this->appstorage);
    }

    /**
     * Main page of game
     * @return [type]
     */
    public function index(): View
    {
        $psychics = $this->PsychicsFactory->takeAll();
        $player = $this->Player;

        return view('home', compact('psychics', 'player'));
    }

    /**
     * Start new game
     * @return Redirector
     */
    public function newgame(): Redirector
    {
        $this->appstorage->clearData();
        return redirect('/');
    }

    /**
     * Run game logic
     * @param Request $request
     * @return View
     */
    public function rungame(Request $request): View
    {
        $psychics = $this->PsychicsFactory->takeAll();

        if ($this->Player->takeTrys() == $psychics[0]->takeTrys()) {
            $this->PsychicsFactory->allMakeJob();
            $psychics = $this->PsychicsFactory->takeAll();
        }

        return view('game.input', compact('psychics'));
    }

    /**
     * Make user bet and take result
     * @param Request $request
     *
     */
    public function bet(Request $request)
    {
        $rules = ['nomber' => 'required|integer|digits:2'];
        $messages = [
            'required' => 'Без числа не можем продолжить игру, оно необходимо',
            'integer' => 'Используйте только целое число в 2 знака',
            'digits' => 'Используйте только целое число между 10 и 99'
        ];

        $winers = [];

        $psychics = $this->PsychicsFactory->takeAll();

        if ($request->ajax()) {
            $input = $request->all();

            $validator = Validator::make($input, $rules, $messages);

            if ($validator->fails()) {
                $errors = $validator->errors();

                return view('game.input', compact('errors', 'psychics'))->withErrors($validator);
            }
            $winers = $this->PsychicsFactory->allCheckResult($input['nomber']);

            $this->PlayerService->updatePlayerData($input['nomber']);

            $this->PsychicsFactory->allClearJob();
        } else {
            return view('game.input', compact('psychics'));
        }

        return view('game.result', compact('winers'));
    }
}
