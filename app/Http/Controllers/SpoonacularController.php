<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class SpoonacularController extends Controller
{
    private $client;
    private $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = '0780e14ba21e45319bef57cfc85ab9cf';
    }

    public function searchRecipes(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {

            $response = $this->client->get('https://api.spoonacular.com/recipes/complexSearch', ['query' => ['apiKey' => $this->apiKey]]);
            $data = json_decode($response->getBody(), true);

            return response()->json(['recipes ' => $data]);
        } catch (GuzzleException $e) {

            return response()->json(['error' => 'API request error']);
        }
    }

    public function getRecipeInformation(Request $request, $id)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {

            $response = $this->client->get("https://api.spoonacular.com/recipes/{$id}/information", ['query' => ['apiKey' => $this->apiKey]]);
            $data = json_decode($response->getBody(), true);

            return response()->json(['recipe ' => $data]);
        } catch (GuzzleException $e) {

            return response()->json(['error' => 'API request error']);
        }
    }
}