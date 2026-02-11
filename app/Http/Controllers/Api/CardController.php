<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskList;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function store(Request $request, $listId)
    {
        $list = TaskList::findOrFail($listId);
        
        if ($request->user()->id !== $list->board->workspace->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $card = $list->cards()->create([
            'title' => $validated['title'],
            'position' => $list->cards()->count()
        ]);

        return response()->json($card, 201);
    }

    public function update(Request $request, $id)
    {
        $card = \App\Models\Card::findOrFail($id);

        if ($request->user()->id !== $card->taskList->board->workspace->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'task_list_id' => 'required|exists:task_lists,id',
            'position' => 'numeric',
        ]);

        $card->update([
            'task_list_id' => $validated['task_list_id']
        ]);

        return response()->json(['message' => 'Card moved successfully']);
    }

    public function destroy(Request $request, $id)
    {
        $card = \App\Models\Card::findOrFail($id);

        if ($request->user()->id !== $card->taskList->board->workspace->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $card->delete();

        return response()->json(['message' => 'Card deleted']);
    }
}