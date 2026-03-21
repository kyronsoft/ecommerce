@extends('layouts.admin', ['breadcrumb' => $product->exists ? 'Editar producto' : 'Nuevo producto'])
@section('content')
<form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">@csrf @if($product->exists) @method('PUT') @endif
    <div class="row gutter-sm"><div class="col-md-6"><label>Nombre</label><input class="form-control mb-3" name="name" value="{{ old('name', $product->name) }}"></div><div class="col-md-6"><label>SKU</label><input class="form-control mb-3" name="sku" value="{{ old('sku', $product->sku) }}"></div></div>
    <div class="row gutter-sm"><div class="col-md-6"><label>Categoría</label><select class="form-control mb-3" name="category_id">@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $product->category_id)==$category->id)>{{ $category->name }}</option>@endforeach</select></div><div class="col-md-3"><label>Precio</label><input class="form-control mb-3" name="price" value="{{ old('price', $product->price) }}"></div><div class="col-md-3"><label>Stock</label><input class="form-control mb-3" name="stock" value="{{ old('stock', $product->stock) }}"></div></div>
    <div class="row gutter-sm"><div class="col-md-6"><label>Precio comparativo</label><input class="form-control mb-3" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}"></div><div class="col-md-6"><label>Imagen</label><input class="form-control mb-3" name="image" value="{{ old('image', $product->image) }}"></div></div>
    <label>Galería (separada por comas)</label><input class="form-control mb-3" name="gallery" value="{{ old('gallery', is_array($product->gallery) ? implode(', ', $product->gallery) : '') }}">
    <label>Descripción corta</label><input class="form-control mb-3" name="short_description" value="{{ old('short_description', $product->short_description) }}">
    <label>Descripción</label><textarea class="form-control mb-3" rows="5" name="description">{{ old('description', $product->description) }}</textarea>
    <div class="mb-3"><label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))> Activo</label> <label class="ml-4"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Destacado</label></div>
    <button class="btn btn-dark btn-rounded">Guardar</button>
</form>
@endsection
