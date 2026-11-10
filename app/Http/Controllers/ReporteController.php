<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Zone;
use App\Models\Report;
use App\Models\ReportImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller{
    public function create()
    {
        $categorias = Category::all();
        $zonas = Zone::all();
        return view('reportes.crear', compact('categorias', 'zonas'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'zone_id' => 'nullable|exists:zones,id',
            'descripcion' => 'required|string',
            'referencia_direccion' => 'nullable|string|max:255',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $reporte = Report::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'zone_id' => $request->zone_id,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'referencia_direccion' => $request->referencia_direccion,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
            'estado' => 'pendiente',
        ]);

        if ($request->hasFile('foto')) {
            $ruta = $request->file('foto')->store('reportes', 'public');

            ReportImage::create([
                'report_id' => $reporte->id,
                'ruta_imagen' => $ruta,
                'tipo' => 'inicial',
            ]);
        }

        return redirect()->route('dashboard')->with('exito', '¡Reporte registrado con éxito!');
    }

    public function index(Request $request)
    {
        $query = Report::with(['category', 'zone', 'user', 'fotoInicial', 'fotoSolucion']);

        if ($request->has('estado') && $request->estado != '') {
            $query->where('estado', $request->estado);
        }

        $reportes = $query->latest()->paginate(10);

        return view('reportes.index', compact('reportes'));
    }

    public function updateEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,en_revision,en_proceso,resuelto,rechazado',
            'notas_moderador' => 'nullable|string|max:1000',
            'foto_solucion' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $reporte = Report::findOrFail($id);
        $reporte->estado = $request->estado;
        $reporte->notas_moderador = $request->notas_moderador;
        $reporte->save();

        if ($request->hasFile('foto_solucion')) {
            $ruta = $request->file('foto_solucion')->store('reportes', 'public');

            ReportImage::create([
                'report_id' => $reporte->id,
                'ruta_imagen' => $ruta,
                'tipo' => 'solucion',
            ]);
        }

        return redirect()->back()->with('exito', 'Estado del reporte actualizado correctamente.');
    }

}
