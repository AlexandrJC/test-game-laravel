<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class SessionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSession()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $sessionData = Session::all();
        $this->assertNotEmpty($sessionData);
    }
}
