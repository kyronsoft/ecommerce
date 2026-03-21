@extends('layouts.admin', [
    'breadcrumb' => 'Categorias',
    'pageTitle' => 'Gestion de categorias',
    'pageDescription' => 'Crea y organiza las familias del catalogo para que la navegacion y el filtrado del ecommerce funcionen correctamente.',
])

@section('page_actions')
    <a href="{{ route('admin.categories.create') }}" class="admin-btn admin-btn--primary">Nueva categoria</a>
@endsection

@section('content')
    <div class="admin-grid-3" style="margin-bottom: 1.8rem;">
        <article class="admin-stat-card">
            <span class="admin-stat-label">Total</span>
            <strong class="admin-stat-value">{{ $stats['total'] }}</strong>
            <span class="admin-stat-help">Categorias registradas</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Con productos</span>
            <strong class="admin-stat-value">{{ $stats['with_products'] }}</strong>
            <span class="admin-stat-help">Listas para alimentar la tienda</span>
        </article>
        <article class="admin-stat-card">
            <span class="admin-stat-label">Vacias</span>
            <strong class="admin-stat-value">{{ $stats['without_products'] }}</strong>
            <span class="admin-stat-help">Categorias pendientes por poblar</span>
        </article>
    </div>

    <section class="admin-panel">
        <div class="admin-toolbar">
            <div class="admin-toolbar-copy">
                <h2>Listado de categorias</h2>
                <p>Define nombre, slug, descripcion e imagen de referencia.</p>
            </div>
        </div>

        @if($categories->isEmpty())
            <div class="admin-empty">
                Aun no has creado categorias. Empieza por la estructura principal del catalogo.
            </div>
        @else
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Categoria</th>
                        <th>Slug</th>
                        <th>Productos</th>
                        <th>Actualizada</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>
                                <strong>{{ $category->name }}</strong>
                                @if($category->description)
                                    <div class="admin-muted">{{ \Illuminate\Support\Str::limit($category->description, 80) }}</div>
                                @endif
                            </td>
                            <td>{{ $category->slug }}</td>
                            <td>{{ $category->products_count }}</td>
                            <td>{{ $category->updated_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.categories.show', $category) }}" class="admin-link">Ver</a>
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="admin-link">Editar</a>
                                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" onsubmit="return confirm('¿Eliminar esta categoria?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-link" style="border: 0; background: transparent; padding: 0;">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                {{ $categories->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </section>
@endsection
