<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Alumno;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str; // <-- Asegúrate de importar esta
use App\Exports\GrupoAlumnosExport; // La clase que creamos
use Maatwebsite\Excel\Facades\Excel; // El facade de la librería
use Illuminate\Support\Facades\DB;

class GrupoController extends Controller
{
    public function index(Request $request)
    {
        // Listado con conteo de alumnos
        $grupos = Grupo::withCount('alumnos')
            ->orderByDesc('pk_grupo')
            ->paginate(10);

        // Tarjetas
        $gruposActuales   = Grupo::where('estatus', 'Activo')->count();
        $gruposAnteriores = Grupo::where('estatus', 'Inactivo')->count();
        $totalTutorados   = Alumno::whereHas('grupo', fn($q) => $q->where('estatus','Activo'))->count();

        // Tu vista de index se llama 'grupo' según tu web.php
        return view('grupo', compact('grupos','gruposActuales','gruposAnteriores','totalTutorados'));
    }

    /**
     * MUESTRA EL DETALLE (Método actualizado)
     * Usamos Route Model Binding (Grupo $grupo)
     */

    public function create()
    {
    return view('crear_grupo'); // tu blade de crear
    }
    
    public function store(Request $request)
    {
    $data = $request->validate([
        'nombre_grupo' => 'required|string|max:255',
        'cuatrimestre' => 'nullable|string|max:255',
        'estatus'      => 'required|in:Activo,Inactivo',
    ]);

    $grupo = Grupo::create($data);

    return redirect()
        ->route('detalle_grupo', $grupo->pk_grupo)
        ->with('status', 'Grupo creado correctamente.');
    }
    public function show(Grupo $grupo)
    {
        // Laravel ya hizo el findOrFail() por ti
        $grupo->load('alumnos'); 
        
        // Tu vista de detalle se llama 'detalle_grupo' según tu web.php
        return view('detalle_grupo', compact('grupo'));
    }

    /**
     * MUESTRA EL FORMULARIO DE EDICIÓN (Método que faltaba)
     */
    public function edit(Grupo $grupo)
    {
        // Laravel ya hizo el findOrFail() por ti
        // Tu vista de edición se llama 'editar_grupo' según tu web.php
        return view('editar_grupo', compact('grupo'));
    }

    /**
     * ACTUALIZA EN LA BD (Método que faltaba)
     * Este método será llamado por la ruta PUT
     */
    public function update(Request $request, Grupo $grupo)
    {
        $data = $request->validate([
            'nombre_grupo' => 'required|string|max:255',
            'cuatrimestre' => 'nullable|string|max:255',
            'estatus'      => 'required|in:Activo,Inactivo',
        ]);

        $grupo->update($data);

        // Redirigimos a la ruta 'detalle_grupo'
        return redirect()->route('detalle_grupo', $grupo->pk_grupo)
            ->with('status', 'Grupo actualizado correctamente.');
    }

    // Exportar a CSV sin librerías externas
    public function exportCsv()
    {
        $filename = 'grupos_'.now()->format('Ymd_His').'.csv';

        $response = new StreamedResponse(function () {
            // Añade \xEF\xBB\xBF (BOM) para forzar UTF-8 en Excel y que lea acentos
            $handle = fopen('php://output', 'w');
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Encabezados
            fputcsv($handle, ['Grupo','Cuatrimestre','Estatus','Alumnos']);

            Grupo::withCount('alumnos')->chunk(200, function ($chunk) use ($handle) {
                foreach ($chunk as $g) {
                    fputcsv($handle, [
                        $g->nombre_grupo,
                        $g->cuatrimestre,
                        $g->estatus,
                        $g->alumnos_count,
                    ]);
                }
            });

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        return $response;
    }

    /**
     * ===== MÉTODO ACTUALIZADO =====
     * Exporta un grupo específico y su lista de alumnos a CSV.
     */
    public function exportAlumnos(Grupo $grupo)
    {
        // Carga la relación de alumnos
        $grupo->load('alumnos');

        // Crea un nombre de archivo único y seguro
        $filename = 'grupo_' . Str::slug(substr($grupo->nombre_grupo, 0, 30)) . '_' . now()->format('Ymd') . '.csv';

        $response = new StreamedResponse(function () use ($grupo) {
            $handle = fopen('php://output', 'w');
            
            // --- Encabezados del Grupo ---
            fputs($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            fputcsv($handle, ['REPORTE DE GRUPO']); 
            fputcsv($handle, []); 

            fputcsv($handle, ['Grupo: ' . $grupo->nombre_grupo]);
            fputcsv($handle, ['Cuatrimestre: ' . ($grupo->cuatrimestre ?? 'N/A')]);
            fputcsv($handle, ['Estatus: ' . $grupo->estatus]);
            fputcsv($handle, ['Total de Alumnos: ' . $grupo->alumnos->count()]);
            
            fputcsv($handle, []); 

            // --- Encabezados de Alumnos ---
            fputcsv($handle, ['LISTA DE ALUMNOS']);
            fputcsv($handle, [
                // 'ID', // <-- ID quitado
                'Nombre Completo',
                'Correo',
                'Estatus'
            ]);

            // --- Datos de Alumnos ---
            if ($grupo->alumnos->isEmpty()) {
                fputcsv($handle, ['No hay alumnos registrados en este grupo.']);
            } else {
                foreach ($grupo->alumnos as $alumno) {
                    fputcsv($handle, [
                        // $alumno->pk_alumno, // <-- ID quitado
                        trim(($alumno->nombre ?? '').' '.($alumno->apellido_paterno ?? '').' '.($alumno->apellido_materno ?? '')),
                        $alumno->correo ?? '—',
                        $alumno->estatus
                    ]);
                }
            }
            
            fclose($handle);
        });

        // Configura las cabeceras para la descarga
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        return $response;
    }

        public function duplicar(Request $request, Grupo $grupo)
    {
        $data = $request->validate([
            'nombre_grupo'          => 'nullable|string|max:255',
            'cuatrimestre'          => 'nullable|string|max:255',
            'estatus'               => 'nullable|in:Activo,Inactivo',
            'incluir_observaciones' => 'nullable|boolean',
        ]);

        // Cargar relaciones para evitar N+1
        $grupo->load(['alumnos.observaciones']);

        return DB::transaction(function () use ($grupo, $data) {
            // 1) Copiar grupo
            $nuevo = $grupo->replicate();
            $nuevo->nombre_grupo = $data['nombre_grupo'] ?? ($grupo->nombre_grupo.' (Copia)');
            $nuevo->cuatrimestre = $data['cuatrimestre'] ?? $grupo->cuatrimestre;
            $nuevo->estatus      = $data['estatus'] ?? $grupo->estatus;
            $nuevo->save();

            $copiarObs = (bool)($data['incluir_observaciones'] ?? true);

            // 2) Copiar alumnos (+ observaciones opcional)
            foreach ($grupo->alumnos as $alumno) {
                $nuevoAlumno = $alumno->replicate();
                $nuevoAlumno->fk_grupo = $nuevo->pk_grupo;
                $nuevoAlumno->save();

                if ($copiarObs && method_exists($alumno, 'observaciones')) {
                    foreach ($alumno->observaciones as $obs) {
                        $nuevaObs = $obs->replicate();
                        $nuevaObs->fk_alumno = $nuevoAlumno->pk_alumno;
                        $nuevaObs->save();
                    }
                }
            }

            return redirect()
                ->route('detalle_grupo', $nuevo->pk_grupo)
                ->with('status', 'Grupo duplicado correctamente.');
        });
    }
}