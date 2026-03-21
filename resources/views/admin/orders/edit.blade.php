@extends('layouts.admin', ['breadcrumb' => 'Actualizar pedido'])
@section('content')
<form method="POST" action="{{ route('admin.orders.update', $order) }}">@csrf @method('PUT')
    <label>Estado</label>
    <select class="form-control mb-3" name="status">@foreach(['pending','paid','processing','shipped','completed','cancelled'] as $status)<option value="{{ $status }}" @selected($order->status === $status)>{{ $status }}</option>@endforeach</select>
    <label>Notas</label>
    <textarea class="form-control mb-3" name="notes" rows="4">{{ old('notes', $order->notes) }}</textarea>
    <button class="btn btn-dark btn-rounded">Guardar</button>
</form>
@endsection
