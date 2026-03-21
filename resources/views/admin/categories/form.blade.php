@extends('layouts.admin', ['breadcrumb' => $category->exists ? 'Editar categoría' : 'Nueva categoría'])
@section('content')
<form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}">@csrf @if($category->exists) @method('PUT') @endif
    <div class="row gutter-sm"><div class="col-md-6"><label>Nombre</label><input class="form-control mb-3" name="name" value="{{ old('name', $category->name) }}"></div><div class="col-md-6"><label>Slug</label><input class="form-control mb-3" name="slug" value="{{ old('slug', $category->slug) }}"></div></div>
    <label>Imagen (ruta pública)</label><input class="form-control mb-3" name="image" value="{{ old('image', $category->image) }}">
    <label>Descripción</label><textarea class="form-control mb-3" rows="4" name="description">{{ old('description', $category->description) }}</textarea>
    <button class="btn btn-dark btn-rounded">Guardar</button>
</form>
@endsection
