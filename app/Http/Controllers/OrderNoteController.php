<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderNote;

class OrderNoteController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'notable_type' => 'required|in:commande,po',
            'notable_id'   => 'required|integer',
            'body'         => 'required|string|max:2000',
        ]);

        OrderNote::create([
            'notable_type' => $request->notable_type,
            'notable_id'   => $request->notable_id,
            'user_id'      => Auth::id(),
            'body'         => $request->body,
        ]);

        return back()->with('note_success', 'Note added.');
    }

    public function destroy(int $id)
    {
        $note = OrderNote::findOrFail($id);

        // Only owner or admin can delete
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $note->delete();

        return back()->with('note_success', 'Note deleted.');
    }
}
