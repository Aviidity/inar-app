<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpoonacularController extends Controller
{
    private $client;
    private $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = env('SPOONACULAR_API_KEY');
    }

    public function searchRecipes(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {

            $response = $this->client->get('https://api.spoonacular.com/recipes/complexSearch', [
                'headers' => [
                    'x-api-key' => $this->apiKey,
                ],
            ]);

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

            $response = $this->client->get("https://api.spoonacular.com/recipes/{$id}/information", [
                'headers' => [
                    'x-api-key' => $this->apiKey,
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            return response()->json(['recipe ' => $data]);
        } catch (GuzzleException $e) {

            return response()->json(['error' => 'API request error']);
        }
    }

    public function searchRecipesByIngredients(Request $request)
    {
        $ingredients = $request->input('ingredients');
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {

            $response = $this->client->get('https://api.spoonacular.com/recipes/findByIngredients', [
                'headers' => [
                    'x-api-key' => $this->apiKey,
                ],
                'query' => [
                    'ingredients' => $ingredients
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return response()->json(['recipes' => $data]);
        } catch (GuzzleException $e) {

            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function createRecipeCard(Request $request)
    {

        $image = $request->file('image');
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'readyInMinutes' => 'required',
            'image' => 'required | file',
            'servings' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {

            $response = $this->client->post("https://api.spoonacular.com/recipes/visualizeRecipe", [
                'headers' => [
                    'x-api-key' => $this->apiKey
                ],
                'multipart' => [
                    [
                        'name' => 'title',
                        'contents' => $request->get('title'),
                    ],
                    [
                        'name' => 'ingredients',
                        'contents' => 'Ingredient', // HARD
                    ],
                    [
                        'name' => 'instructions',
                        'contents' => 'Instruction', // HARD
                    ],
                    [
                        'name' => 'readyInMinutes',
                        'contents' => $request->get('readyInMinutes'),
                    ],
                    [
                        'name' => 'servings',
                        'contents' => $request->get('servings'),
                    ],
                    [
                        'name' => 'image',
                        'contents' => fopen($image->getPathname(), 'r'),
                    ],
                    [
                        'name' => 'mask',
                        'contents' => 'ellipseMask', // HARD
                    ],
                    [
                        'name' => 'backgroundImage',
                        'contents' => 'none', // HARD
                    ],
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            return response()->json(['recipe ' => $data]);
        } catch (GuzzleException $e) {

            return response()->json(['error' => 'API request error']);
        }
    }
}
