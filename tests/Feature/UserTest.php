<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test register.
     *
     * @return void
     */
    public function test_register()
    {
        Artisan::call('migrate');

        // formulario de registro
        $register = $this->get(route(('register')));
        $register->assertStatus(200)->assertSee('Register');

        // registro incorrecto
        $badRecord = $this->post(route(('register'), ["email"=>"asdf", "password"=>"123"]));
        $badRecord->assertStatus(302)->assertRedirect(route('register'));

        // registro correcto
        $goodRecord = $this->post(route(('register'), [
            "name"=>"testing",
            "email"=>"email2@email.com", 
            "password"=>"admin123",
            "password_confirmation"=>"admin123"]));
        $goodRecord->assertStatus(302)->assertRedirect(route('home'));
        $this->assertDatabaseHas('users',['email'=>"email2@email.com"]);

    }

    /**
     * A basic feature test login.
     *
     * @return void
     */
    public function test_login()
    {
        Artisan::call('migrate');

        // crear usuario
        User::create([
            "name"=>"testing nuevo",
            "email"=>"email3@email.com", 
            "password"=>Hash::make('admin123')
        ]);

        // formulario de login
        $login = $this->get(route(('login')));
        $login->assertStatus(200)->assertSee('Login');

        // intento de acceso no autorizado
        $notAuthorized = $this->get(route(('home')));
        $notAuthorized->assertStatus(302)->assertRedirect(route('login'));

        // error de credenciales
        $badAccess = $this->post(route(('login'), [
            "email"=>"email3@email.com", 
            "password"=>"123",
        ]));
        $badAccess->assertStatus(302);
        
        // acceso correcto
        $goodAccess = $this->post(route(('login'), [
            "email"=>"email3@email.com", "password"=>"admin123"]));
        $goodAccess->assertStatus(302)->assertRedirect(route('home'));

        // lista de favoritos
        $list = $this->get(route(('home')));
        $list->assertStatus(200)->assertSee('Quotes');

        // Logout
        $logout = $this->post(route(('logout')));
        $logout->assertStatus(302);
    }
}
