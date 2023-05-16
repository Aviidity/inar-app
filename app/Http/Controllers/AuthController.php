<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);
        
        return response()->json(['message' => 'User signed up successfully'], 201);
    }
    // Route::get('/user/{id}', [UserController::class, 'show']);

    // Crear un cliente con una URI base
    // $client = new GuzzleHttp\Client(['base_uri' => 'https://foo.com/api/']);
    // Enviar una solicitud a https://foo.com/api/test
    // $response = $client->request('GET', 'test');
    // Enviar una solicitud a https://foo.com/root
    // $response = $client->request('GET', '/root');

    // $client = new Client();
    // $response = $client->post('https://api.example.com/newresource', [
    //      'body' => json_encode ([
    //          'food' => 'Bacalao con guisantes',
    //          'category' => 'Healthy',
    //      ]),
    //      'headers' => [
    //          'Accept' => 'application/vnd.github.v3+json',
    //          'Authorization' => 'token TU_ACCESS_TOKEN',
    //     ]
        
    // ]);
    // $body = $response->getBody();

}
