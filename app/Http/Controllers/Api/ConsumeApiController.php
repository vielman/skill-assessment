<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Http;

class ConsumeApiController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $apiEndpoint = $_ENV['API_ENDPOINT_RANDOM'];
            $apiKey = $_ENV['API_KEY_ENDPOINT'];
            $quote = Http::withHeaders([
                'accept' => 'application/json',
                'Authorization' => 'Bearer '.$apiKey,
            ])->get($apiEndpoint,  [
                'limit' => '5',
                'language' => 'en'
            ]);
            return response()->json(["message" => "quote OK", "data" => $quote->json()], Response::HTTP_OK);
        } catch (ValidationException $e) {
            $error = $e->validator->errors()->toArray();
            return response(["message" => "Error validation ", "data" =>$error], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return response()->json(["message" => "Error: ".$e->getMessage(), "data" =>[]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }       
        
    }

     /**
     * Display the specified resource.
     *
     * @param  $autor
     * @param  $limit
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        try {
            $request->validate([
                'autor' => ['required']
            ]);

            $autor = $request->autor;
            $limit  = ($request->limit) ? $request->limit : 1 ; 
            $apiEndpoint = $_ENV['API_ENDPOINT_SEARCH'];
            $apiKey = $_ENV['API_KEY_ENDPOINT'];
            $quote = Http::withHeaders([
                'accept' => 'application/json',
                'Authorization' => 'Bearer '.$apiKey,
            ])->get($apiEndpoint,  [
                'author' => $autor,
                'limit' => $limit,
                'minlength' => '100',
                'maxlength'=> '300',
                'private' => 'false',
                'language' => 'en',
                'sfw' => 'false'
            ]);
            return response()->json(["message" => "quote OK", "data" => $quote->json()], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(["message" => "Error ".$e->getMessage(), "data" =>[]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }        
    }
}
