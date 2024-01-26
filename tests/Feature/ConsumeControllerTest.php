<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tests\TestCase;

class ConsumeControllerTest extends TestCase
{
    use RefreshDatabase;


    public function test_get_quotes_list() {
        // crear usuario
        $user = User::create([
            "name"=>"testing nuevo",
            "email"=>"email@email.com", 
            "password"=>Hash::make('admin123')
        ]);

        // acceso login
        $this->post(route(('login'), ["email"=>"email@email.com", "password"=>"admin123"]));

        $respuesta = $this->getJson('/api/quotes');
        
        // respuesta OK
        $respuesta->assertOk();
        
    }

}
