<x-app-layout>
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h4 class="card-title fw-bold mb-3"><i class="bi bi-kanban me-2"></i>Panel de Gestión de Incidencias Urbanas</h4>

            @if(session('exito'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>{{ session('exito') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="GET" action="{{ route('reportes.index') }}" class="row g-3 align-items-center">
                <div class="col-auto">
                    <label class="col-form-label fw-bold">Filtrar por estado:</label>
                </div>
                <div class="col-auto">
                    <select name="estado" onchange="this.form.submit()" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                        <option value="en_revision" {{ request('estado') == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                        <option value="en_proceso" {{ request('estado') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                        <option value="resuelto" {{ request('estado') == 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                        <option value="rechazado" {{ request('estado') == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Folio</th>
                            <th>Título / Categoría</th>
                            <th>Ciudadano</th>
                            <th>Estado</th>
                            <th>Foto Inicial</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportes as $reporte)
                            <tr>
                                <td class="fw-bold">#{{ $reporte->id }}</td>
                                <td>
                                    <div class="fw-bold">{{ $reporte->titulo }}</div>
                                    <small class="text-muted">{{ $reporte->category->nombre ?? 'Sin categoría' }}</small>
                                </td>
                                <td>
                                    <small class="fw-bold d-block">{{ $reporte->user->nombre ?? 'Usuario' }}</small>
                                    <small class="text-muted">{{ $reporte->user->telefono }}</small>
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($reporte->estado == 'pendiente') bg-warning text-dark
                                        @elseif($reporte->estado == 'en_revision') bg-secondary
                                        @elseif($reporte->estado == 'en_proceso') bg-primary
                                        @elseif($reporte->estado == 'resuelto') bg-success
                                        @else bg-danger @endif">
                                        {{ strtoupper(str_replace('_', ' ', $reporte->estado)) }}
                                    </span>
                                </td>
                                <td>
                                    @if($reporte->fotoInicial)
                                        <a href="{{ asset('storage/' . $reporte->fotoInicial->ruta_imagen) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $reporte->fotoInicial->ruta_imagen) }}" class="rounded img-thumbnail" style="width: 55px; height: 55px; object-fit: cover;">
                                        </a>
                                    @else
                                        <small class="text-muted">Sin foto</small>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-info mb-1 w-100 btn-ver-detalle"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalDetalleReporte"
                                            data-folio="{{ $reporte->id }}"
                                            data-titulo="{{ $reporte->titulo }}"
                                            data-categoria="{{ $reporte->category->nombre ?? 'Sin categoría' }}"
                                            data-zona="{{ $reporte->zone->nombre ?? 'Sin zona' }}"
                                            data-ciudadano="{{ $reporte->user->nombre ?? 'N/A' }}"
                                            data-telefono="{{ $reporte->user->telefono ?? 'N/A' }}"
                                            data-descripcion="{{ $reporte->descripcion }}"
                                            data-referencia="{{ $reporte->referencia_direccion ?? 'Sin referencia' }}"
                                            data-estado="{{ strtoupper(str_replace('_', ' ', $reporte->estado)) }}"
                                            data-latitud="{{ $reporte->latitud }}"
                                            data-longitud="{{ $reporte->longitud }}"
                                            data-foto-inicial="{{ $reporte->fotoInicial ? asset('storage/' . $reporte->fotoInicial->ruta_imagen) : '' }}"
                                            data-foto-solucion="{{ $reporte->fotoSolucion ? asset('storage/' . $reporte->fotoSolucion->ruta_imagen) : '' }}"
                                            data-notas="{{ $reporte->notas_moderador ?? '' }}">
                                        <i class="bi bi-eye-fill me-1"></i> Ver Detalle
                                    </button>

                                    <!-- Formulario para cambiar estado -->
                                    <form action="{{ route('reportes.updateEstado', $reporte->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PATCH')
                                        <div class="input-group input-group-sm mb-1">
                                            <select name="estado" class="form-select">
                                                <option value="pendiente" {{ $reporte->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                                <option value="en_revision" {{ $reporte->estado == 'en_revision' ? 'selected' : '' }}>En Revisión</option>
                                                <option value="en_proceso" {{ $reporte->estado == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                                <option value="resuelto" {{ $reporte->estado == 'resuelto' ? 'selected' : '' }}>Resuelto</option>
                                                <option value="rechazado" {{ $reporte->estado == 'rechazado' ? 'selected' : '' }}>Rechazado</option>
                                            </select>
                                            <button type="submit" class="btn btn-dark">Guardar</button>
                                        </div>
                                        <input type="file" name="foto_solucion" accept="image/*" class="form-control form-control-sm">
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No hay reportes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $reportes->links() }}
    </div>

    <!-- MODAL DE DETALLE DEL REPORTE -->
    <div class="modal fade" id="modalDetalleReporte" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="modalDetalleLabel">
                        <i class="bi bi-file-earmark-text me-2"></i>Detalle del Reporte #<span id="modalFolio"></span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">

                    <div class="row mb-3">
                        <div class="col-md-7">
                            <h4 class="fw-bold text-primary mb-1" id="modalTitulo"></h4>
                            <p class="mb-2">
                                <span class="badge bg-secondary" id="modalCategoria"></span>
                                <span class="badge bg-outline-dark border text-dark" id="modalZona"></span>
                                <span class="badge bg-info text-dark" id="modalEstado"></span>
                            </p>

                            <p class="mb-1"><strong>Ciudadano:</strong> <span id="modalCiudadano"></span> (<span id="modalTelefono"></span>)</p>
                            <p class="mb-1"><strong>Referencia:</strong> <span id="modalReferencia"></span></p>
                            
                            <div class="mt-3">
                                <h6><strong>Descripción:</strong></h6>
                                <p class="text-muted bg-light p-2 rounded border" id="modalDescripcion"></p>
                            </div>

                            <div id="contenedorNotas" class="mt-2 d-none">
                                <h6><strong>Notas de Moderación:</strong></h6>
                                <p class="text-dark bg-warning-subtle p-2 rounded border border-warning" id="modalNotas"></p>
                            </div>
                        </div>

                        <!-- Evidencias Fotográficas -->
                        <div class="col-md-5">
                            <h6><strong>Evidencia Inicial:</strong></h6>
                            <div class="mb-3">
                                <img id="modalImgInicial" src="" class="img-fluid rounded border shadow-sm w-100" style="max-height: 180px; object-fit: cover;" alt="Sin Foto Inicial">
                            </div>

                            <div id="contenedorImgSolucion" class="d-none">
                                <h6><strong>Evidencia de Solución:</strong></h6>
                                <img id="modalImgSolucion" src="" class="img-fluid rounded border shadow-sm w-100" style="max-height: 180px; object-fit: cover;" alt="Sin Foto Solución">
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Mapa en el Modal -->
                    <h6 class="fw-bold"><i class="bi bi-geo-alt-fill me-1 text-danger"></i>Ubicación Exacta de la Incidencia</h6>
                    <div id="mapaModal" style="height: 300px;" class="rounded border shadow-sm"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT JS PARA EL MAPA Y MODAL -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var mapModal = null;
            var markerModal = null;
            var currentLat = 0;
            var currentLng = 0;

            // Escuchar clic en los botones "Ver Detalle"
            document.querySelectorAll('.btn-ver-detalle').forEach(function (button) {
                button.addEventListener('click', function () {
                    document.getElementById('modalFolio').textContent = this.dataset.folio;
                    document.getElementById('modalTitulo').textContent = this.dataset.titulo;
                    document.getElementById('modalCategoria').textContent = this.dataset.categoria;
                    document.getElementById('modalZona').textContent = this.dataset.zona;
                    document.getElementById('modalEstado').textContent = this.dataset.estado;
                    document.getElementById('modalCiudadano').textContent = this.dataset.ciudadano;
                    document.getElementById('modalTelefono').textContent = this.dataset.telefono;
                    document.getElementById('modalReferencia').textContent = this.dataset.referencia;
                    document.getElementById('modalDescripcion').textContent = this.dataset.descripcion;

                    currentLat = parseFloat(this.dataset.latitud);
                    currentLng = parseFloat(this.dataset.longitud);

                    // Foto Inicial
                    var imgInicial = document.getElementById('modalImgInicial');
                    if (this.dataset.fotoInicial) {
                        imgInicial.src = this.dataset.fotoInicial;
                        imgInicial.parentElement.classList.remove('d-none');
                    } else {
                        imgInicial.src = 'https://via.placeholder.com/300x180?text=Sin+Foto+Inicial';
                    }

                    // Foto Solución
                    var contenedorSolucion = document.getElementById('contenedorImgSolucion');
                    var imgSolucion = document.getElementById('modalImgSolucion');
                    if (this.dataset.fotoSolucion) {
                        imgSolucion.src = this.dataset.fotoSolucion;
                        contenedorSolucion.classList.remove('d-none');
                    } else {
                        contenedorSolucion.classList.add('d-none');
                    }

                    // Notas Moderador
                    var contenedorNotas = document.getElementById('contenedorNotas');
                    var modalNotas = document.getElementById('modalNotas');
                    if (this.dataset.notas) {
                        modalNotas.textContent = this.dataset.notas;
                        contenedorNotas.classList.remove('d-none');
                    } else {
                        contenedorNotas.classList.add('d-none');
                    }
                });
            });

            // Evento para inicializar o centrar el mapa cuando el modal es visible
            var modalElement = document.getElementById('modalDetalleReporte');
            modalElement.addEventListener('shown.bs.modal', function () {
                if (!mapModal) {
                    mapModal = L.map('mapaModal').setView([currentLat, currentLng], 15);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(mapModal);

                    markerModal = L.marker([currentLat, currentLng]).addTo(mapModal);
                } else {
                    mapModal.setView([currentLat, currentLng], 15);
                    markerModal.setLatLng([currentLat, currentLng]);
                }

                // Corregir dimensiones de renderizado del mapa
                setTimeout(function() {
                    mapModal.invalidateSize();
                }, 200);
            });
        });
    </script>
</x-app-layout>