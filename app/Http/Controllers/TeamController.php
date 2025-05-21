<?php

namespace App\Http\Controllers;

use App\Models\TeamModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

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
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'division_id' => 'required|exists:divisions,division_id',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }

        try {
            $imageName = null;

            if ($request->hasFile('logo')) {
                // Store the file in the logo_images folder
                $imagePath = $request->file('logo')->store('logo_images', 'public');

                // Extract only the file name
                $imageName = basename($imagePath);
            }

            // Create a new team
            $team = TeamModel::create([
                'name' => $request->name,
                'alias' => $request->alias,
                'logo' => $imageName,
                'division_id' => $request->division_id,
            ]);

            return response()->json([
                'message' => 'Team created successfully',
                'team' => $team,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating the team',
                'team name' => $request->name,
                'division_id' => $request->division_id,
                'logo' => $request->logo,
                'alias' => $request->alias,
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
            'name' => 'required|string|max:255',
            'alias' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 422);
        }

        if ($request->hasFile('logo')) {
            // Delete the old logo if it exists
            $team = TeamModel::find($id);
            if ($team && $team->logo) {
                Storage::disk('public')->delete($team->logo);
            }
            $imagePath = $request->file('logo')->store('logo_images', 'public');
            $imageName = basename($imagePath);
        } else {
            $imagePath = null;
        }

        try {
            $team = TeamModel::findOrFail($id);
            $team->update($request->only(['name', 'alias'])); // Exclude 'logo' from the update here

            if ($imagePath) {
                $team->logo = $imageName; // Update the logo only if a new one is provided
                $team->save();
            }

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
