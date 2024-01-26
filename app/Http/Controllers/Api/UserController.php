<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = User::where('is_admin', 0)->get();
            return response()->json(["message" => "users OK", "data" => $user], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
             return response()->json(["message" => "Record not found ", "data" =>[]], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(["message" => "Error ".$e->getMessage(), "data" =>[]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }       
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json(["message" => "user OK", "data" => $user], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
             return response()->json(["message" => "Record not found ", "data" =>[]], Response::HTTP_NOT_FOUND);
        } catch (Exception $e) {
            return response()->json(["message" => "Error ".$e->getMessage(), "data" =>[]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }        
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function banning($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->is_active = 0;
            $user->save();
            return response()->json(["message" => "User OK", "data" => $user], Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return response()->json(["message" => "Error ".$e->getMessage(), "data" =>[]], Response::HTTP_INTERNAL_SERVER_ERROR);
        }   
    }
    
}
