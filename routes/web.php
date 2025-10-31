<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\Alumno;
use App\Http\Controllers\EmpresaController;
use App\Models\OpcionEstadia;
use App\Http\Controllers\ReporteEstadiaController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('encuesta', 'encuesta')
    ->middleware(['auth', 'verified'])
    ->name('encuesta');

Route::view('canalizaciones', 'canalizaciones')
    ->middleware(['auth', 'verified'])
    ->name('canalizaciones');

Route::get('estadias', function () {
    $alumnos = Alumno::with('opcionesEstadia.empresa')->get();
    $empresas = Empresa::all();

    return view('layouts.estadias', [
        'alumnos' => $alumnos,
        'empresas' => $empresas
    ]);
})->middleware(['auth', 'verified'])->name('estadias');

Route::put('/alumnos/{alumno}/opciones', function(Request $request, Alumno $alumno) {

    $validated = $request->validate([
        'opcion_1_id' => 'nullable|integer|exists:empresa,pk_empresa',
        'opcion_2_id' => 'nullable|integer|exists:empresa,pk_empresa',
        'opcion_3_id' => 'nullable|integer|exists:empresa,pk_empresa',
    ]);

    $alumno->opcionesEstadia()->updateOrCreate(
        ['opcion_numero' => 1],
        ['fk_empresa' => $validated['opcion_1_id']]
    );

    $alumno->opcionesEstadia()->updateOrCreate(
        ['opcion_numero' => 2],
        ['fk_empresa' => $validated['opcion_2_id']]
    );

    $alumno->opcionesEstadia()->updateOrCreate(
        ['opcion_numero' => 3],
        ['fk_empresa' => $validated['opcion_3_id']]
    );

    return response()->json([
        'success' => true,
        'message' => 'Opciones actualizadas correctamente'
    ]);
})->middleware(['auth', 'verified']);

Route::patch('/opciones-estadia/{opcion}/status', function(Request $request, OpcionEstadia $opcion) {

    $validated = $request->validate([
        'estatus' => 'required|string|in:Pendiente,Contactado,No Contactado,Aceptado,Rechazado'
    ]);

    $opcion->update([
        'estatus' => $validated['estatus']
    ]);

    return response()->json(['success' => true, 'message' => 'Estatus actualizado.']);

})->middleware(['auth', 'verified'])->name('opciones-estadia.updateStatus');


Route::view('empresas/crear', 'layouts.nueva_empresa')
    ->middleware(['auth', 'verified'])
    ->name('empresas.create');

Route::post('empresas', function (Request $request) {
    $request->validate([
        'nombre_empresa' => 'required|string|max:150',
        'nombre_contacto' => 'nullable|string|max:200',
        'email' => 'nullable|email|max:100',
        'telefono' => 'nullable|string|max:20',
        'direccion' => 'nullable|string',
        'notas' => 'nullable|string',
    ]);

    Empresa::create([
        'nombre' => $request->input('nombre_empresa'),
        'nombre_contacto' => $request->input('nombre_contacto'),
        'tel' => $request->input('telefono'),
        'correo' => $request->input('email'),
        'direccion' => $request->input('direccion'),
        'notas' => $request->input('notas'),
        'estatus' => 'Activa'
    ]);

    return redirect()->route('estadias')->with('success', '¡Empresa registrada con éxito!');
})->middleware(['auth', 'verified'])->name('empresas.store');

Route::get('estadias/reporte', [ReporteEstadiaController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('estadias.reporte');


Route::get('estadias/reporte/pdf', function() {
    return "Página de descarga de PDF (aún no implementada)";
})
->middleware(['auth', 'verified'])
->name('estadias.reporte.pdf');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');
    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

require __DIR__.'/auth.php';

Route::put('/empresas/{empresa}', [EmpresaController::class, 'update'])->name('empresas.update');
Route::delete('/empresas/{empresa}', [EmpresaController::class, 'destroy'])->name('empresas.destroy');

