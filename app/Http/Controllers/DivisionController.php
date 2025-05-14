<?php

namespace App\Http\Controllers;

use App\Models\DivisionModel;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $divisions = DivisionModel::all();

        // Return the divisions as a JSON response
        return response()->json($divisions, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }

        try {
            $division = DivisionModel::create($request->only(['name']));
            return response()->json([
                'message' => 'Division created successfully',
                'division' => $division,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating/updating the division',
                'name' => $request->name,
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $division = DivisionModel::find($id);

        if (!$division) {
            return response()->json(['error' => 'Division not found'], 404);
        }

        return response()->json($division, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()], 401);
        }

        try {
            $division = DivisionModel::find($id);

            if (!$division) {
                return response()->json(['error' => 'Division not found'], 404);
            }

            $division->update($request->only(['name']));

            return response()->json([
                'message' => 'Division updated successfully',
                'division' => $division,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating/updating the division',
                'name' => $request->name,
            ], 500);
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $division = DivisionModel::find($id);

        if (!$division) {
            return response()->json(['error' => 'Division not found'], 404);
        }

        try {
            $division->delete();
            return response()->json(['message' => 'Division deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the division'], 500);
        }
    }


    public function getTeamsByDivision($divisionId)
    {
        $teams = DivisionModel::findOrFail($divisionId)->teams;
        return response()->json($teams);
    }
}
