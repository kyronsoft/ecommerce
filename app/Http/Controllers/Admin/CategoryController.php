<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('products')->orderBy('name')->paginate(15),
            'stats' => [
                'total' => Category::count(),
                'with_products' => Category::has('products')->count(),
                'without_products' => Category::doesntHave('products')->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.form', ['category' => new Category()]);
    }

    public function show(Category $category): View
    {
        return view('admin.categories.show', [
            'category' => $category->load(['products' => fn ($query) => $query->latest()->take(10)]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Category::create($this->validated($request));
        return redirect()->route('admin.categories.index')->with('status', 'Categoría creada.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $category->update($this->validated($request));
        return redirect()->route('admin.categories.index')->with('status', 'Categoría actualizada.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return redirect()
                ->route('admin.categories.index')
                ->with('status', 'No puedes eliminar una categoría que todavía tiene productos asociados.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('status', 'Categoría eliminada.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => [
                'nullable',
                'string',
                'max:150',
                Rule::unique('categories', 'slug')->ignore($request->route('category')),
            ],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
