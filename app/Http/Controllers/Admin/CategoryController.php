<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Support\AdminPanelScope;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(): View
    {
        [, $adminStore, $isSuperAdmin] = AdminPanelScope::fromRequest(request());

        return view('admin.categories.index', [
            'categories' => AdminPanelScope::scopeCategories(Category::query(), $adminStore, $isSuperAdmin)->withCount('products')->orderBy('name')->paginate(15),
            'stats' => [
                'total' => AdminPanelScope::scopeCategories(Category::query(), $adminStore, $isSuperAdmin)->count(),
                'with_products' => AdminPanelScope::scopeCategories(Category::query(), $adminStore, $isSuperAdmin)->has('products')->count(),
                'without_products' => AdminPanelScope::scopeCategories(Category::query(), $adminStore, $isSuperAdmin)->doesntHave('products')->count(),
            ],
        ]);
    }

    public function create(): View
    {
        abort_unless(request()->attributes->get('adminIsSuperAdmin'), 403);
        return view('admin.categories.form', ['category' => new Category()]);
    }

    public function show(Category $category): View
    {
        [, $adminStore, $isSuperAdmin] = AdminPanelScope::fromRequest(request());
        AdminPanelScope::ensureCategoryAccess($category, $adminStore, $isSuperAdmin);
        return view('admin.categories.show', [
            'category' => $category->load(['products' => fn ($query) => AdminPanelScope::scopeProducts($query, $adminStore, $isSuperAdmin)->latest()->take(10)]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless($request->attributes->get('adminIsSuperAdmin'), 403);
        Category::create($this->validated($request));
        return redirect()->route('admin.categories.index')->with('status', 'Categoría creada.');
    }

    public function edit(Category $category): View
    {
        abort_unless(request()->attributes->get('adminIsSuperAdmin'), 403);
        return view('admin.categories.form', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        abort_unless($request->attributes->get('adminIsSuperAdmin'), 403);
        $category->update($this->validated($request));
        return redirect()->route('admin.categories.index')->with('status', 'Categoría actualizada.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        abort_unless(request()->attributes->get('adminIsSuperAdmin'), 403);
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
