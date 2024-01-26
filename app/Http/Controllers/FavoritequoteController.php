<?php

namespace App\Http\Controllers;

use App\Models\Favoritequote;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FavoritequoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = auth()->user()->id;
            $favoritequote = Favoritequote::where('user_id',$user)->get();
            return response()->json(["message" => "favoriteQuote OK", "data" => $favoritequote], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
             return response()->json(["message" => "Record not found ", "data" =>[]], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(["message" => "Error ".$e->getMessage(), "data" =>[]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }       
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'quote' => ['required'],
                'author' => ['required'],
                'user_id' => ['required', 'exists:App\Models\User,id']
            ]);
            
            $numberQuotesSaved = auth()->user()->number_quotes_saved;
            $countQuotes = count(Favoritequote::where('user_id', $request->user_id)->get());
            if ($countQuotes >= $numberQuotesSaved) {
                return response(["message" => "You have exceeded the limit of saved quotes: ", "data" =>['number_quotes_saved'=> $numberQuotesSaved]], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $favoritequote = new Favoritequote;
            $favoritequote->quote       = $request->quote;
            $favoritequote->author      = $request->author;
            $favoritequote->user_id     = $request->user_id;
            $favoritequote->save();
    
            return response()->json(["message" => "favoriteQuote OK", "data" => $favoritequote], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            $error = $e->validator->errors()->toArray();
            return response(["message" => "Error creating record: ", "data" =>$error], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            return response()->json(["message" => "Error ".$e->getMessage(), "data" =>[]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }   
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Favoritequote  $favoritequote
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $favoritequote = Favoritequote::findOrFail($id);
            return response()->json(["message" => "favoriteQuote OK", "data" => $favoritequote], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
             return response()->json(["message" => "Record not found ", "data" =>[]], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(["message" => "Error ".$e->getMessage(), "data" =>[]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Favoritequote  $favoritequote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Favoritequote $favoritequote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Favoritequote  $favoritequote
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $favoritequote = Favoritequote::findOrFail($id);
            $favoritequote->delete();
            return response()->noContent();
        } catch (ModelNotFoundException $e) {
             return response()->json(["message" => "Record not found ", "data" =>[]], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(["message" => "Error ".$e->getMessage(), "data" =>[]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }        
    }
}
