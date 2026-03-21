@extends('layouts.admin', [
    'breadcrumb' => $customer->exists ? 'Editar cliente' : 'Nuevo cliente',
    'pageTitle' => $customer->exists ? 'Editar cliente' : 'Crear cliente',
    'pageDescription' => 'Captura los datos del cliente con departamento y ciudad oficiales del DANE para mantener una base consistente.',
])

@section('page_actions')
    <a href="{{ route('admin.customers.index') }}" class="admin-btn">Volver al listado</a>
    @if($customer->exists)
        <a href="{{ route('admin.customers.show', $customer) }}" class="admin-btn admin-btn--primary">Ver detalle</a>
    @endif
@endsection

@section('content')
    <form method="POST" action="{{ $customer->exists ? route('admin.customers.update', $customer) : route('admin.customers.store') }}">
        @csrf
        @if($customer->exists)
            @method('PUT')
        @endif

        <div class="admin-grid-2">
            <section class="admin-panel">
                <div class="admin-section-head">
                    <div>
                        <h2>Datos del cliente</h2>
                        <p>Informacion principal para pedidos, contacto y trazabilidad.</p>
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="first_name">Nombres</label>
                        <input id="first_name" type="text" name="first_name" class="form-control" value="{{ old('first_name', $customer->first_name) }}" required>
                    </div>
                    <div class="admin-field">
                        <label for="last_name">Apellidos</label>
                        <input id="last_name" type="text" name="last_name" class="form-control" value="{{ old('last_name', $customer->last_name) }}" required>
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
                    </div>
                    <div class="admin-field">
                        <label for="phone">Telefono</label>
                        <input id="phone" type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
                    </div>
                </div>

                <div class="admin-form-grid">
                    <div class="admin-field">
                        <label for="department_code">Departamento</label>
                        <select id="department_code" name="department_code" class="form-control" required>
                            <option value="">Selecciona un departamento</option>
                            @foreach($departments as $department)
                                <option value="{{ $department['code'] }}" @selected($selectedDepartmentCode === $department['code'])>
                                    {{ $department['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="admin-field">
                        <label for="city_code">Ciudad</label>
                        <select
                            id="city_code"
                            name="city_code"
                            class="form-control"
                            data-selected-city="{{ $selectedCityCode }}"
                            required
                            disabled
                        >
                            <option value="">Primero elige un departamento</option>
                        </select>
                    </div>
                </div>

                <div class="admin-field">
                    <label for="address">Direccion</label>
                    <input id="address" type="text" name="address" class="form-control" value="{{ old('address', $customer->address) }}">
                </div>
            </section>

            <section class="admin-panel">
                <div class="admin-section-head">
                    <div>
                        <h2>Control de calidad</h2>
                        <p>Usa la division politico-administrativa oficial para evitar inconsistencias en checkout y envios.</p>
                    </div>
                </div>

                <div class="admin-kv">
                    <div class="admin-kv-item">
                        <span class="admin-kv-label">Nombre completo</span>
                        <span class="admin-kv-value">{{ trim(old('first_name', $customer->first_name).' '.old('last_name', $customer->last_name)) ?: 'Sin definir' }}</span>
                    </div>
                    <div class="admin-kv-item">
                        <span class="admin-kv-label">Email de contacto</span>
                        <span class="admin-kv-value">{{ old('email', $customer->email ?: 'Sin definir') }}</span>
                    </div>
                    <div class="admin-kv-item">
                        <span class="admin-kv-label">Ubicacion</span>
                        <span class="admin-kv-value">{{ collect([$customer->city, $customer->department])->filter()->implode(', ') ?: 'Selecciona departamento y ciudad' }}</span>
                    </div>
                    <div class="admin-kv-item">
                        <span class="admin-kv-label">Consejo</span>
                        <span class="admin-kv-value">Registra estos datos antes de crear pedidos manuales para ahorrar tiempo operativo.</span>
                    </div>
                </div>
            </section>
        </div>

        <div class="admin-actions" style="margin-top: 1.8rem;">
            <button type="submit" class="admin-btn admin-btn--primary">Guardar cliente</button>
            <a href="{{ route('admin.customers.index') }}" class="admin-btn admin-btn--secondary">Cancelar</a>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const departmentSelect = document.getElementById('department_code');
            const citySelect = document.getElementById('city_code');
            const citiesByDepartment = @json($citiesByDepartment);

            function renderCities(departmentCode, selectedCity = '') {
                const cities = citiesByDepartment[departmentCode] || [];
                citySelect.innerHTML = '';

                if (!departmentCode || cities.length === 0) {
                    citySelect.disabled = true;
                    citySelect.innerHTML = '<option value="">Primero elige un departamento</option>';
                    return;
                }

                citySelect.disabled = false;
                citySelect.append(new Option('Selecciona una ciudad', ''));

                cities.forEach((city) => {
                    citySelect.add(new Option(city.name, city.code, false, city.code === selectedCity));
                });
            }

            renderCities(departmentSelect.value, citySelect.dataset.selectedCity || '');
            departmentSelect.addEventListener('change', () => renderCities(departmentSelect.value));
        });
    </script>
@endpush
