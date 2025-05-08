<?php

namespace App\Http\Controllers;

use App\Models\GameModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $games = GameModel::with(['homeTeam', 'awayTeam'])->get();

        return response()->json($games, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'home_team_id' => 'required|integer|exists:teams,team_id',
            'away_team_id' => 'required|integer|exists:teams,team_id',
            'game_date' => 'required|date',
            'game_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'home_team_final_score' => 'nullable|integer|min:0',
            'away_team_final_score' => 'nullable|integer|min:0',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }

        if ($request->home_team_final_score < 0 || $request->away_team_final_score < 0) {
            return response()->json(['error' => 'Final scores cannot be negative'], 400);
        }

        if ($request->home_team_id === $request->away_team_id) {
            return response()->json(['error' => 'Home team and away team cannot be the same'], 400);
        }

        try {
            // Create a new game
            $game = GameModel::create($request->only([
                'home_team_id',
                'away_team_id',
                'game_date',
                'game_time',
                'location',
                'status',
                'home_team_final_score',
                'away_team_final_score'
            ]));

            return response()->json($game, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create game'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $game = GameModel::with(['homeTeam', 'awayTeam'])->find($id);
        if (!$game) {
            return response()->json(['error' => 'Game not found'], 404);
        }

        return response()->json($game, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'home_team_id' => 'required|integer|exists:teams,team_id',
            'away_team_id' => 'required|integer|exists:teams,team_id',
            'game_date' => 'required|date',
            'game_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'home_team_final_score' => 'nullable|integer|min:0',
            'away_team_final_score' => 'nullable|integer|min:0',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }

        if ($request->home_team_final_score < 0 || $request->away_team_final_score < 0) {
            return response()->json(['error' => 'Final scores cannot be negative'], 400);
        }

        if ($request->home_team_id === $request->away_team_id) {
            return response()->json(['error' => 'Home team and away team cannot be the same'], 400);
        }

        $game = GameModel::find($id);
        if (!$game) {
            return response()->json(['error' => 'Game not found'], 404);
        }

        try {
            // Update the game
            $game->update($request->only([
                'home_team_id',
                'away_team_id',
                'game_date',
                'game_time',
                'location',
                'status',
                'home_team_final_score',
                'away_team_final_score'
            ]));

            return response()->json($game, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update game'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $game = GameModel::find($id);
            if (!$game) {
                return response()->json(['error' => 'Game not found'], 404);
            }

            $game->delete();

            return response()->json(['message' => 'Game deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete game'], 500);
        }
    }
}
