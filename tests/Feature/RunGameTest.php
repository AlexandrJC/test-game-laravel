<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class RunGameTest extends TestCase
{
    /**
     * A game run test example with good input.
     *
     * @return void
     */
    public function testGoodRunGame()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->post('/rungame');
        $response->assertStatus(200);

        $response = $this->post('/bet', [
            'nomber' => '12'
        ]);

        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();

        $response->assertSee('А угадал только');
    }

    /**
     * A game run test example with error input 4.
     *
     * @return void
     */
    public function testErrorDigitInputRunGame()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->post('/rungame');
        $response->assertStatus(200);

        $response = $this->ajaxPost('/bet', ['nomber' => '4']);
        $response->assertStatus(200);
        $response->assertSee('Используйте только целое число между 10 и 99');
    }

    /**
     * A game run test example with error input no data
     *
     * @return void
     */
    public function testErrorClearInputRunGame()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->post('/rungame');
        $response->assertStatus(200);

        $response = $this->ajaxPost('bet');
        $response->assertStatus(200);

        $response->assertSee('Без числа не можем продолжить игру, оно необходимо');
    }

     /**
     * A game run test example with error input string
     *
     * @return void
     */
    public function testErrorStringInputRunGame()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response = $this->post('/rungame');
        $response->assertStatus(200);

        $response = $this->ajaxPost('/bet', ['nomber' => 'asdad']);
        $response->assertStatus(200);

        $response->assertSee('Используйте только целое число в 2 знака');
    }


    /**
     * Make ajax POST request
     */
    protected function ajaxPost($uri, array $data = [])
    {
        return $this->post($uri, $data, array('HTTP_X-Requested-With' => 'XMLHttpRequest'));
    }

    /**
     * Make ajax GET request
     */
    protected function ajaxGet($uri, array $data = [])
    {
        return $this->get($uri, array('HTTP_X-Requested-With' => 'XMLHttpRequest'));
    }
}
