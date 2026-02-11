<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Workspace;
use App\Http\Resources\WorkspaceResource;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function index(Request $request)
    {
        $workspaces = $request->user()->workspaces; 
        
        return WorkspaceResource::collection($workspaces);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $workspace = $request->user()->workspaces()->create($validated);

        return new WorkspaceResource($workspace);
    }

    public function show(Request $request, Workspace $workspace)
    {
        if ($request->user()->id !== $workspace->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new WorkspaceResource($workspace);
    }

    public function destroy(Request $request, Workspace $workspace)
    {
        if ($request->user()->id !== $workspace->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $workspace->delete();

        return response()->json(['message' => 'Workspace deleted successfully'], 200);
    }
}
