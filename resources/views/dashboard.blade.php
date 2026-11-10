<x-app-layout>
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <h3 class="fw-bold mb-3">Bienvenido, {{ Auth::user()->nombre }}</h3>

            @if(session('exito'))
                <div class="alert alert-success">{{ session('exito') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if(Auth::user()->rol === 'usuario')
                <p class="text-muted">Desde este panel puedes registrar incidencias urbanas con geolocalización y darles seguimiento.</p>
                <a href="{{ route('reportes.create') }}" class="btn btn-primary btn-lg fw-bold">
                    <i class="bi bi-plus-circle me-1"></i> Registrar Nuevo Reporte
                </a>
            @elseif(in_array(Auth::user()->rol, ['moderador', 'administrador']))
                <p class="text-muted">Tienes permisos administrativos para gestionar los reportes recibidos.</p>
                <a href="{{ route('reportes.index') }}" class="btn btn-dark btn-lg fw-bold">
                    <i class="bi bi-kanban me-1"></i> Ir al Panel de Gestión
                </a>
            @endif
        </div>
    </div>
</x-app-layout>