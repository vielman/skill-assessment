<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use App\Models\Favoritequote;
use App\Models\User;
use Tests\TestCase;

class FavoriteControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_favorite_quote_list() {
        // crear usuario
        $user = User::create([
            "name"=>"testing nuevo",
            "email"=>"email4@email.com", 
            "password"=>Hash::make('admin123')
        ]);

        // crear citas fovoritas
        $favorite = Favoritequote::create([
            "quote"=>"If you want to lift yourself up, lift up someone else.",
            "author"=>"Booker T. Washington", 
            "user_id"=>$user->id
        ]);

        // acceso login
        $this->post(route(('login'), ["email"=>"email4@email.com", "password"=>"admin123"]));

        $respuesta = $this->getJson('/api/favoritequotes');
        
        // respuesta OK
        $respuesta->assertOk();
        
        // respuesta Json
        $respuesta->assertJson([
            "message" => "favoriteQuote OK",
            "data" => [$favorite->toArray()],
        ]);
    }

    public function test_create_favorite_quote() {
        // crear usuario
        $user = User::create([
            "name"=>"testing nuevo",
            "email"=>"email5@email.com", 
            "password"=>Hash::make('admin123')
        ]);

        // acceso login
        $this->post(route(('login'), ["email"=>"email5@email.com", "password"=>"admin123"]));

        $respuesta = $this->postJson('/api/favoritequotes', [
            "quote"=>"We become what we think about.",
            "author"=>"Earl Nightingale", 
            "user_id"=>$user->id
        ]);

        // respuesta OK
        $respuesta->assertCreated();
        
        // respuesta Json
        $respuesta->assertJson([
            "message" => "favoriteQuote OK",
            "data" => [
                "quote"=>"We become what we think about.",
                "author"=>"Earl Nightingale", 
                "user_id"=>$user->id
            ],
        ]);

        // comprobar que esta en la base de datos
        $this->assertDatabaseHas('favoritequotes', [
            "quote"=>"We become what we think about."
        ]);
    }

    public function test_delete_favorite_quote() {
        // crear usuario
        $user = User::create([
            "name"=>"testing nuevo",
            "email"=>"email4@email.com", 
            "password"=>Hash::make('admin123')
        ]);

        // crear citas fovoritas
        $favorite = Favoritequote::create([
            "quote"=>"If you want to lift yourself up, lift up someone else.",
            "author"=>"Booker T. Washington", 
            "user_id"=>$user->id
        ]);

        // acceso login
        $this->post(route(('login'), ["email"=>"email4@email.com", "password"=>"admin123"]));

        $respuesta = $this->deleteJson("/api/favoritequotes/{$favorite->id}");
        
        // respuesta OK
        $respuesta->assertNoContent();

        // comprobar que ya no esta en la base de datos
        $this->assertDatabaseMissing('favoritequotes', $favorite->toArray());
        
    }

}
