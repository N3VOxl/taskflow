<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    // GET /api/boards/{board_id}/lists
    public function index(Request $request, $boardId)
    {
        $board = Board::findOrFail($boardId);

        // Verificăm permisiunea
        if ($request->user()->id !== $board->workspace->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Returnăm listele IMPREUNĂ cu cardurile lor, ordonate
        $lists = $board->taskLists()->with(['cards' => function ($query) {
            $query->orderBy('position');
        }])->orderBy('position')->get();

        return response()->json($lists);
    }

    // POST /api/boards/{board_id}/lists
    public function store(Request $request, $boardId)
    {
        $board = Board::findOrFail($boardId);

        if ($request->user()->id !== $board->workspace->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $list = $board->taskLists()->create([
            'name' => $validated['name'],
            'position' => $board->taskLists()->count() // O punem la final
        ]);

        return response()->json($list, 201);
    }
}