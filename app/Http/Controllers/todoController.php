<?php

namespace App\Http\Controllers;

use App\Models\todos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class todoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = Auth::user()->todos;
        return response()->json($todos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255'
        ]);

        $todo = Todos::create([
            'title' => $request->title,
            'completed' => false,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'tâche créée avec succes',
            'todo' => $todo
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $todo = todos::where('id', $id)->where('user_id', Auth::id())->first();

        if(!$todo){
            return response()->json([
                'message' => 'tache introuvable',
            ], 404);
        }
        return response()->json($todo);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $todo = todos::where('id', $id)->where('user_id', Auth::id())->first();

        if(!$todo){
            return response()->json([
                'message' => 'tache introuvable',
            ], 404);
        }
        $todo->update($request->only(['title', 'completed']));

        return response()->json([
            'message' => 'tache effectuée',
            'todo' => $todo,
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $todo = todos::where('id', $id)->where('user_id', Auth::id())->first();
        if(!$todo){
            return response()->json([
                'message' => 'tache introuvable',
            ], 404);
        }
        $todo->delete();

        return response()->json([
            'message' => 'tâche supprimée '
        ]);
    }
}
