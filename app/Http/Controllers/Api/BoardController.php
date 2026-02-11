<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Workspace;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function index(Request $request, $workspaceId)
    {
        $workspace = Workspace::findOrFail($workspaceId);

        if ($request->user()->id !== $workspace->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($workspace->boards);
    }

    public function store(Request $request, $workspaceId)
    {
        $workspace = Workspace::findOrFail($workspaceId);

        if ($request->user()->id !== $workspace->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $board = $workspace->boards()->create([
            'title' => $validated['title'],
            'color' => '#3b82f6' // Default Blue
        ]);

        return response()->json($board, 201);
    }
}