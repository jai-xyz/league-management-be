<?php

namespace App\Http\Controllers;

use App\Models\TeamModel;
use Illuminate\Http\Request;

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
    public function createUpdateTeam()
    {
        //
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
