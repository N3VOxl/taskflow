<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskList;
use Illuminate\Http\Request;

class CardController extends Controller
{
    // POST /api/lists/{list_id}/cards
    public function store(Request $request, $listId)
    {
        $list = TaskList::findOrFail($listId);
        
        // Verificăm permisiunea prin relația List -> Board -> Workspace -> User
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

    // PATCH /api/cards/{card} - Mutarea cardului
    public function update(Request $request, $id)
    {
        $card = \App\Models\Card::findOrFail($id);

        // Verificăm permisiunea (prin relația Card -> List -> Board -> Workspace -> User)
        if ($request->user()->id !== $card->taskList->board->workspace->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'task_list_id' => 'required|exists:task_lists,id',
            'position' => 'numeric', // Opțional, dacă vrei să salvezi și ordinea
        ]);

        $card->update([
            'task_list_id' => $validated['task_list_id']
        ]);

        return response()->json(['message' => 'Card moved successfully']);
    }

    // DELETE /api/cards/{card} - Ștergerea cardului
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