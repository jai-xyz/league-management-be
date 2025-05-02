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
    public function getTeamList()
    {
        // Get all teams
        $teams = TeamModel::all();

        // Return the teams as a JSON response
        return response()->json($teams, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createUpdateTeam(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id' => 'nullable|integer|exists:teams,id',
            'name' => 'required|string|max:255',
            'alias' => 'required|string|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }

        try {
            DB::beginTransaction();

            if ($request->has('id')) {
                // Update the existing team
                $team = TeamModel::findOrFail($request->id);
                $team->update([
                    'name' => $request->name,
                    'alias' => $request->alias,
                ]);
            } else {
                // Create a new team
                $team = TeamModel::create([
                    'name' => $request->name,
                    'alias' => $request->alias,
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => $request->has('id') ? 'Team updated successfully' : 'Team created successfully',
                'team' => $team,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
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
    public function viewTeam(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        // Get the team by ID
        $team = TeamModel::findOrFail($request->id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        // Return the team as a JSON response
        return response()->json($team, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function deleteTeam(TeamModel $teamModel)
    {
        //
    }
}
