<x-app-layout>
    <div class="row justify-content-center">
        <div class="col-md-9 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-geo-alt-fill me-2"></i>Crear Nuevo Reporte Urbano</h5>
                </div>
                <div class="card-body p-4">

                    <form action="{{ route('reportes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Título -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Título de la incidencia</label>
                            <input type="text" name="titulo" class="form-control" placeholder="Ej. Fuga de agua o Bache profundo" required>
                        </div>

                        <!-- Categoría y Zona -->
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-bold">Categoría</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Zona / Sector (Opcional)</label>
                                <select name="zone_id" class="form-select">
                                    <option value="">Seleccione una zona</option>
                                    @foreach($zonas as $zona)
                                        <option value="{{ $zona->id }}">{{ $zona->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Descripción detallada</label>
                            <textarea name="descripcion" rows="3" class="form-control" placeholder="Describe brevemente el problema..." required></textarea>
                        </div>

                        <!-- Referencia -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Referencia de dirección</label>
                            <input type="text" name="referencia_direccion" placeholder="Ej. Frente a la plaza principal" class="form-control">
                        </div>

                        <!-- Foto Inicial -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Evidencia Fotográfica (1 Foto)</label>
                            <input type="file" name="foto" accept="image/*" class="form-control" required>
                        </div>

                        <!-- Mapa Leaflet -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Selecciona la ubicación exacta en el mapa</label>
                            <div id="mapa" style="height: 350px;" class="rounded border"></div>
                        </div>

                        <!-- Coordenadas Ocultas -->
                        <input type="hidden" name="latitud" id="latitud">
                        <input type="hidden" name="longitud" id="longitud">

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                <i class="bi bi-send-fill me-1"></i> Enviar Reporte
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Script de Leaflet.js -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var latInicial = 23.7369;
            var lngInicial = -99.1411;

            var map = L.map('mapa').setView([latInicial, lngInicial], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var marcador;

            map.on('click', function (e) {
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;

                if (marcador) {
                    marcador.setLatLng(e.latlng);
                } else {
                    marcador = L.marker(e.latlng).addTo(map);
                }

                document.getElementById('latitud').value = lat;
                document.getElementById('longitud').value = lng;
            });
        });
    </script>
</x-app-layout>