<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::all();

        return view('note.index', compact('notes'));
    }

    public function create()
    {
        return view('note.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        Note::create($request->all());

        return redirect('/note')
            ->with('success', 'Catatan berhasil dibuat!');
    }
    // ...

    public function edit($id)
    {
        $note = Note::find($id);
        return view('note.edit', compact('note'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $note = Note::find($id);
        $note->update($request->all());

        return redirect('/note')
            ->with('success', 'Catatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $note = Note::find($id);
        $note->delete();

        return redirect('/note')
            ->with('success', 'Catatan berhasil dihapus!');
    }

    // ...

}
