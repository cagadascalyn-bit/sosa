<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::where('user_id', auth()->id())->latest()->get();
        return view('recipes.index', compact('recipes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'ingredients'  => 'required|string',
            'instructions' => 'required|string',
            'prep_time'    => 'required|integer|min:1',
            'image'        => 'nullable|image|max:2048',
        ]);

        $data = $request->only('title', 'category', 'ingredients', 'instructions', 'prep_time');
        $data['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('recipes', 'public');
        }

        Recipe::create($data);
        return redirect()->route('recipes.index')->with('toast_success', 'Recipe added successfully!');
    }

    public function update(Request $request, Recipe $recipe)
    {
        if ($recipe->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'ingredients'  => 'required|string',
            'instructions' => 'required|string',
            'prep_time'    => 'required|integer|min:1',
            'image'        => 'nullable|image|max:2048',
        ]);

        $data = $request->only('title', 'category', 'ingredients', 'instructions', 'prep_time');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('recipes', 'public');
        }

        $recipe->update($data);
        return redirect()->route('recipes.index')->with('toast_success', 'Recipe updated successfully!');
    }

    public function destroy(Recipe $recipe)
    {
        if ($recipe->user_id !== auth()->id()) {
            abort(403);
        }
        $recipe->delete();
        return redirect()->route('recipes.index')->with('toast_success', 'Recipe deleted successfully!');
    }
}
