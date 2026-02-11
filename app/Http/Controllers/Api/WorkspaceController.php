<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use App\Http\Resources\WorkspaceResource;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    // GET /api/workspaces (Arată toate workspace-urile userului curent)
    public function index(Request $request)
    {
        // Preluăm doar workspace-urile userului logat
        $workspaces = $request->user()->workspaces; 
        
        // Folosim Resource pentru a formata răspunsul (Cerința JSON Responses)
        return WorkspaceResource::collection($workspaces);
    }

    // POST /api/workspaces (Creează un workspace nou)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Creăm workspace-ul legat automat de userul logat
        $workspace = $request->user()->workspaces()->create($validated);

        return new WorkspaceResource($workspace);
    }

    // GET /api/workspaces/{id} (Arată un singur workspace)
    public function show(Request $request, Workspace $workspace)
    {
        // Securitate: Verificăm dacă userul deține acest workspace
        if ($request->user()->id !== $workspace->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new WorkspaceResource($workspace);
    }

    // DELETE /api/workspaces/{id} (Șterge un workspace)
    public function destroy(Request $request, Workspace $workspace)
    {
        if ($request->user()->id !== $workspace->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $workspace->delete();

        return response()->json(['message' => 'Workspace deleted successfully'], 200);
    }
}
