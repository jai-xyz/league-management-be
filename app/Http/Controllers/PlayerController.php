<?php

namespace App\Http\Controllers;

use App\Models\PlayerModel;
use App\Models\TeamModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // "team" here is Team word on TeamModel
        $players = PlayerModel::with('team')->get();

        return response()->json($players, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'team_id' => 'required|integer|exists:teams,team_id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'height' => 'required|integer|min:0',
            'weight' => 'required|integer|min:0',
            'position' => 'required|string|max:255',
            'jersey_number' => 'required|integer|min:0',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }

        if ($request->has('team_id')) {
            $team = TeamModel::find($request->team_id);
            if (!$team) {
                return response()->json(['error' => 'Team not found'], 404);
            }

            try {
                // Create a new player
                $player = PlayerModel::create($request->only([
                    'team_id',
                    'first_name',
                    'middle_name',
                    'nickname',
                    'last_name',
                    'age',
                    'height',
                    'weight',
                    'position',
                    'jersey_number'
                ]));

                return response()->json([
                    'message' => 'Player created successfully',
                    'player' => $player,
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'An error occurred while creating the player',
                    'details' => $e->getMessage(),
                ], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'team_id' => 'required|integer|exists:teams,team_id',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'height' => 'required|integer|min:0',
            'weight' => 'required|integer|min:0',
            'position' => 'required|string|max:255',
            'jersey_number' => 'required|integer|min:0',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }

        $player = PlayerModel::find($id);
        if (!$player) {
            return response()->json(['error' => 'Player not found'], 404);
        }

        if ($request->has('team_id')) {
            $team = TeamModel::find($request->team_id);
            if (!$team) {
                return response()->json(['error' => 'Team not found'], 404);
            }

            try {
                // Update a new player
                $player->update($request->only([
                    'team_id',
                    'first_name',
                    'middle_name',
                    'nickname',
                    'last_name',
                    'age',
                    'height',
                    'weight',
                    'position',
                    'jersey_number'
                ]));

                return response()->json([
                    'message' => 'Player update successfully',
                    'player' => $player,
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'An error occurred while updating the player',
                    'details' => $e->getMessage(),
                ], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $player = PlayerModel::find($id);
            if (!$player) {
                return response()->json(['error' => 'Player not found'], 404);
            }

            $player->delete();

            return response()->json(['message' => 'Player deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while deleting the player',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
