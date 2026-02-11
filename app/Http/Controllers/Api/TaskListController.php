<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\TaskList;
use Illuminate\Http\Request;

class TaskListController extends Controller
{
    public function index(Request $request, $boardId)
    {
        $board = Board::findOrFail($boardId);

        if ($request->user()->id !== $board->workspace->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $lists = $board->taskLists()->with(['cards' => function ($query) {
            $query->orderBy('position');
        }])->orderBy('position')->get();

        return response()->json($lists);
    }

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
            'position' => $board->taskLists()->count()
        ]);

        return response()->json($list, 201);
    }
}