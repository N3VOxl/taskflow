<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Workspace;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    // --- ACEASTA ESTE FUNCȚIA CARE ÎȚI LIPSEA (INDEX) ---
    // GET /api/workspaces/{workspace_id}/boards
    public function index(Request $request, $workspaceId)
    {
        // 1. Găsim workspace-ul
        $workspace = Workspace::findOrFail($workspaceId);

        // 2. Verificăm permisiunea (doar proprietarul vede board-urile)
        if ($request->user()->id !== $workspace->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // 3. Returnăm board-urile asociate
        return response()->json($workspace->boards);
    }

    // --- FUNCȚIA PENTRU CREARE (STORE) ---
    // POST /api/workspaces/{workspace_id}/boards
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