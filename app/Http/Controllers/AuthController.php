<?php

namespace App\Http\Controllers;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required | email | unique:users',
            'password' => 'required | min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);
        
        return response()->json(['message' => 'User signed up successfully'], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required | email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json(['error' => 'Email is not registered'], 404);
        }

        if (!password_verify($request->input('password'), $user->password)) {
            return response()->json(['error' => 'Incorrect password'], 400);
        }

        $payload = [
            'iss' => config('app.url'),
            'sub' => $user->id,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24),
        ];

        $jwt = JWT::encode($payload, config('app.key'), 'HS256');

        return response()->json(['token' => $jwt]);
    }

    public function logout(Request $request){
        
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['message' => 'Successfully logged out']);
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
