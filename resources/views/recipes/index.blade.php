@extends('layouts.app')
@section('title', 'My Recipes')
@section('page-title', 'My Recipes')

@section('content')
<div class="card">
    <div class="p-4">
        <div class="card-header-bar">
            <div>
                <h6 style="margin:0;font-weight:800"><i class="bi bi-journal-richtext me-2" style="color:var(--primary)"></i>Recipe Collection</h6>
                <div style="font-size:.78rem;color:#888;margin-top:2px">{{ $recipes->count() }} recipes in your list</div>
            </div>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addRecipeModal">
                <i class="bi bi-plus-lg me-1"></i>Add Recipe
            </button>
        </div>

        @if($recipes->isEmpty())
        <div class="text-center py-5">
            <div style="font-size:4rem">🍽️</div>
            <div style="font-weight:800;font-size:1.1rem;margin-top:12px">No recipes yet!</div>
            <div style="color:#888;font-size:.88rem;margin-top:4px">Start building your personal recipe collection.</div>
            <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addRecipeModal">
                <i class="bi bi-plus-lg me-1"></i>Add Your First Recipe
            </button>
        </div>
        @else
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Recipe</th>
                        <th>Category</th>
                        <th>Prep Time</th>
                        <th>Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recipes as $i => $recipe)
                    <tr>
                        <td style="color:#bbb;font-size:.8rem">{{ str_pad($i+1,2,'0',STR_PAD_LEFT) }}</td>
                        <td>
                            @if($recipe->image)
                                <img src="{{ asset('storage/'.$recipe->image) }}"
                                     style="width:52px;height:42px;object-fit:cover;border-radius:10px;border:2px solid #f0ebe5">
                            @else
                                <div style="width:52px;height:42px;border-radius:10px;background:#f7f3ef;display:flex;align-items:center;justify-content:center;font-size:1.3rem">
                                    🍽️
                                </div>
                            @endif
                        </td>
                        <td style="font-weight:700">{{ $recipe->title }}</td>
                        <td><span class="badge-category">{{ $recipe->category }}</span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:5px;font-size:.85rem">
                                <i class="bi bi-clock" style="color:var(--primary)"></i>
                                {{ $recipe->prep_time }} min
                            </div>
                        </td>
                        <td style="color:#888;font-size:.82rem">{{ $recipe->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm"
                                    style="background:#f0fff4;color:#27ae60;border:none;border-radius:8px;padding:5px 10px"
                                    onclick="viewRecipe('{{ addslashes($recipe->title) }}','{{ addslashes($recipe->category) }}','{{ $recipe->prep_time }}',`{{ addslashes($recipe->ingredients) }}`,`{{ addslashes($recipe->instructions) }}`)">
                                    <i class="bi bi-eye-fill"></i>
                                </button>
                                <button class="btn btn-sm"
                                    style="background:#f0f7ff;color:#2980b9;border:none;border-radius:8px;padding:5px 10px"
                                    onclick="openEditRecipe({{ $recipe->id }},'{{ addslashes($recipe->title) }}','{{ $recipe->category }}',`{{ addslashes($recipe->ingredients) }}`,`{{ addslashes($recipe->instructions) }}`,{{ $recipe->prep_time }})">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <form method="POST" action="{{ route('recipes.destroy', $recipe) }}" class="d-inline"
                                      onsubmit="return confirm('Delete this recipe?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm" style="background:#fff0f0;color:#e74c3c;border:none;border-radius:8px;padding:5px 10px">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- Add Recipe Modal --}}
<div class="modal fade" id="addRecipeModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form method="POST" action="{{ route('recipes.store') }}" enctype="multipart/form-data" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle-fill me-2"></i>Add New Recipe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Recipe Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Chicken Adobo" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="">Select...</option>
                            <option>Breakfast</option><option>Lunch</option><option>Dinner</option>
                            <option>Snack</option><option>Dessert</option><option>Drinks</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prep Time (minutes)</label>
                        <input type="number" name="prep_time" class="form-control" min="1" placeholder="e.g. 30" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Recipe Image <span style="color:#bbb;font-weight:400">(optional)</span></label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ingredients</label>
                        <textarea name="ingredients" class="form-control" rows="4"
                            placeholder="List ingredients, one per line..." required></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Instructions</label>
                        <textarea name="instructions" class="form-control" rows="4"
                            placeholder="Step-by-step cooking instructions..." required></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm" style="background:#f0ebe5;border:none;border-radius:8px;font-weight:700;padding:8px 18px" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-check-lg me-1"></i>Save Recipe
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Edit Recipe Modal --}}
<div class="modal fade" id="editRecipeModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <form method="POST" id="editRecipeForm" enctype="multipart/form-data" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-fill me-2"></i>Edit Recipe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Recipe Title</label>
                        <input type="text" name="title" id="eTitle" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Category</label>
                        <select name="category" id="eCategory" class="form-select" required>
                            <option>Breakfast</option><option>Lunch</option><option>Dinner</option>
                            <option>Snack</option><option>Dessert</option><option>Drinks</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Prep Time (minutes)</label>
                        <input type="number" name="prep_time" id="ePrepTime" class="form-control" min="1" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">New Image <span style="color:#bbb;font-weight:400">(optional)</span></label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Ingredients</label>
                        <textarea name="ingredients" id="eIngredients" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Instructions</label>
                        <textarea name="instructions" id="eInstructions" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm" style="background:#f0ebe5;border:none;border-radius:8px;font-weight:700;padding:8px 18px" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-check-lg me-1"></i>Update Recipe
                </button>
            </div>
        </form>
    </div>
</div>

{{-- View Recipe Modal --}}
<div class="modal fade" id="viewRecipeModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="vTitle"></h5>
                    <div style="font-size:.78rem;color:rgba(255,255,255,.5);margin-top:2px">
                        <span id="vCategory"></span> &bull; <i class="bi bi-clock me-1"></i><span id="vPrepTime"></span> min
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-5">
                        <div style="background:#f7f3ef;border-radius:12px;padding:16px">
                            <div style="font-weight:800;font-size:.85rem;color:var(--primary);margin-bottom:10px">
                                <i class="bi bi-list-ul me-2"></i>Ingredients
                            </div>
                            <pre id="vIngredients" style="white-space:pre-wrap;font-size:.85rem;margin:0;font-family:'Nunito',sans-serif;color:#444"></pre>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div style="background:#f7f3ef;border-radius:12px;padding:16px">
                            <div style="font-weight:800;font-size:.85rem;color:var(--primary);margin-bottom:10px">
                                <i class="bi bi-journal-text me-2"></i>Instructions
                            </div>
                            <pre id="vInstructions" style="white-space:pre-wrap;font-size:.85rem;margin:0;font-family:'Nunito',sans-serif;color:#444"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openEditRecipe(id, title, category, ingredients, instructions, prepTime) {
    document.getElementById('editRecipeForm').action = '/recipes/' + id;
    document.getElementById('eTitle').value        = title;
    document.getElementById('eCategory').value     = category;
    document.getElementById('ePrepTime').value     = prepTime;
    document.getElementById('eIngredients').value  = ingredients;
    document.getElementById('eInstructions').value = instructions;
    new bootstrap.Modal(document.getElementById('editRecipeModal')).show();
}
function viewRecipe(title, category, prepTime, ingredients, instructions) {
    document.getElementById('vTitle').textContent        = title;
    document.getElementById('vCategory').textContent     = category;
    document.getElementById('vPrepTime').textContent     = prepTime;
    document.getElementById('vIngredients').textContent  = ingredients;
    document.getElementById('vInstructions').textContent = instructions;
    new bootstrap.Modal(document.getElementById('viewRecipeModal')).show();
}
</script>
@endsection
