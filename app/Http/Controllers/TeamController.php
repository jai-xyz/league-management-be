<?php

namespace App\Http\Controllers;

use App\Models\TeamModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = TeamModel::all();

        // Return the teams as a JSON response
        return response()->json($teams, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'alias' => 'required|string|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }

        try {
            // Create a new team
            $team = TeamModel::create($request->only(['name', 'alias']));
            return response()->json([
                'message' => 'Team created successfully',
                'team' => $team,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating/updating the team',
                'name' => $request->name,
                'team' => $request->alias,
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $team = TeamModel::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        return response()->json($team, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            // 'team_id' => 'required|integer|exists:teams,id',
            'name' => 'required|string|max:255',
            'alias' => 'required|string|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }

        try {
            $team = TeamModel::findOrFail($id);
            $team->update($request->only(['name', 'alias']));
            return response()->json(['message' => 'Team updated successfully', 'team' => $team], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the team', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $team = TeamModel::findOrFail($id);
            $team->delete();
            return response()->json(['message' => 'Team deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the team', 'details' => $e->getMessage()], 500);
        }
    }
}
