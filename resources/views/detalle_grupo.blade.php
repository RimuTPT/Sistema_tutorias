<x-layouts.app :title="__('Detalle del Grupo')">
    <div class="flex h-screen overflow-hidden">
        <main class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-[#262626] overflow-y-auto">

            {{-- Back --}}
            <div class="flex items-center gap-4 mb-6">
                <a href="{{ route('grupo') }}"
                   class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-neutral-300 hover:text-gray-900 dark:hover:text-neutral-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Volver</span>
                </a>
            </div>
            

            {{-- Header --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-neutral-100">
                            {{ $grupo->nombre_grupo }}
                        </h1>

                        @if(($grupo->estatus ?? '') === 'Activo')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900">
                                Actual
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-200 dark:bg-neutral-700 text-gray-900 dark:text-neutral-100">
                                Anterior
                            </span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-500 dark:text-neutral-400">
                        {{ $grupo->cuatrimestre ?? '—' }}
                    </p>
                </div>

                <div class="flex items-center space-x-3 mt-4 md:mt-0">
                    
                    {{-- ===== BOTÓN MODIFICADO ===== --}}
                    {{-- Ahora es "Exportar Grupo" y apunta a la nueva ruta --}}
                    <a href="{{ route('grupos.exportAlumnos', $grupo->pk_grupo) }}"
                        class="flex items-center space-x-2 px-4 py-2 text-sm font-medium text-white bg-[#2B8A7F] border border-transparent rounded-lg shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        {{-- Icono de Descarga --}}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        <span>Exportar Grupo</span>
                    </a>

                    <button type="button" id="openDuplicateModal"
                        class="flex items-center space-x-2 px-4 py-2 text-sm font-medium text-white bg-[#2B8A7F] border border-transparent rounded-lg shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h8m-8 4h8m-8 4h6M5 7h.01M5 11h.01M5 15h.01" />
                        </svg>
                        <span>Duplicar grupo</span>
                    </button>



                    {{-- Editar grupo (secondary) --}}
                    <a href="{{ route('editar_grupo', $grupo->pk_grupo) }}"
                        class="flex items-center space-x-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-700 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-neutral-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        <span>Editar grupo</span>
                    </a>
                </div>


            </div>

            {{-- ... (El resto de tu código de stats y la tabla de alumnos sigue igual) ... --}}
            @php
                $total = $grupo->alumnos->count();
                $activos = $grupo->alumnos->where('estatus', 'Activo')->count();
                $bajas = $grupo->alumnos->where('estatus', 'Baja')->count();
            @endphp

            {{-- Stats --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-neutral-900 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-neutral-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">Total Alumnos</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-neutral-100 mt-2">{{ $total }}</p>
                    <p class="text-sm text-gray-400 dark:text-neutral-500 mt-1">Registrados en el grupo</p>
                </div>
                <div class="bg-white dark:bg-neutral-900 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-neutral-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">Alumnos Activos</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-neutral-100 mt-2">{{ $activos }}</p>
                    <p class="text-sm text-gray-400 dark:text-neutral-500 mt-1">Sin baja registrada</p>
                </div>
                <div class="bg-white dark:bg-neutral-900 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-neutral-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">Bajas</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-neutral-100 mt-2">{{ $bajas }}</p>
                    <p class="text-sm text-gray-400 dark:text-neutral-500 mt-1">Marcados como baja</p>
                </div>
            </div>

            {{-- Modal: Duplicar grupo --}}
            <div id="duplicateModal" class="fixed inset-0 z-[80] hidden">
            {{-- Overlay --}}
            <div id="duplicateOverlay" class="absolute inset-0 bg-black/50 backdrop-blur-[2px]"></div>

            {{-- Dialog --}}
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="w-full max-w-lg rounded-2xl border shadow-2xl
                bg-white dark:bg-neutral-900
                border-gray-200 dark:border-neutral-700">

                {{-- Header --}}
                <div class="flex items-start justify-between p-5 border-b border-gray-200 dark:border-neutral-700">
                    <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-neutral-100">
                        Duplicar grupo
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-neutral-400">
                        Se copiarán el grupo, todos sus alumnos y sus observaciones.
                    </p>
                    </div>
                    <button id="closeDuplicateModal"
                    class="ml-3 inline-flex items-center justify-center rounded-full p-2
                        text-gray-500 hover:text-gray-800 hover:bg-gray-100
                        dark:text-neutral-400 dark:hover:text-neutral-100 dark:hover:bg-neutral-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    </button>
                </div>

                {{-- Body (Form) --}}
                <form method="POST" action="{{ route('grupos.duplicar', $grupo->pk_grupo) }}">
                    @csrf
                    <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-200">
                        Nombre del nuevo grupo
                        </label>
                        <input type="text" name="nombre_grupo" maxlength="255"
                        value="{{ $grupo->nombre_grupo }} (Copia)"
                        class="mt-1 w-full rounded-lg border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-200">
                            Cuatrimestre (opcional)
                        </label>
                        <input type="text" name="cuatrimestre" maxlength="255"
                            value="{{ $grupo->cuatrimestre }}"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 focus:ring-indigo-500 focus:border-indigo-500" />
                        </div>

                        <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-neutral-200">
                            Estatus
                        </label>
                        <select name="estatus"
                            class="mt-1 w-full rounded-lg border-gray-300 dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="Activo"  @selected(($grupo->estatus ?? 'Activo')==='Activo')>Activo</option>
                            <option value="Inactivo" @selected(($grupo->estatus ?? '')==='Inactivo')>Inactivo</option>
                        </select>
                        </div>
                    </div>

                    <label class="inline-flex items-center mt-2 select-none">
                        <input type="checkbox" name="incluir_observaciones" value="1" checked
                        class="w-4 h-4 text-indigo-600 bg-white dark:bg-neutral-800 border-gray-300 dark:border-neutral-600 rounded focus:ring-indigo-500 focus:ring-2">
                        <span class="ml-2 text-sm text-gray-700 dark:text-neutral-300">
                        Incluir observaciones de alumnos
                        </span>
                    </label>

                    <div class="rounded-xl border p-3 text-xs
                        bg-indigo-50 border-indigo-200 text-indigo-800
                        dark:bg-indigo-900/40 dark:border-indigo-800 dark:text-indigo-200">
                        Tip: podrás editar el nombre, cuatrimestre o estatus del nuevo grupo después.
                    </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-5 pb-5 flex items-center justify-end gap-3">
                    <button type="button" id="cancelDuplicateModal"
                        class="px-4 py-2 text-sm font-medium rounded-lg border
                        bg-white text-gray-700 hover:bg-gray-50
                        dark:bg-neutral-900 dark:text-neutral-200 dark:border-neutral-700 dark:hover:bg-neutral-800">
                        Cancelar
                    </button>

                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium rounded-lg shadow-sm border
                        text-white bg-indigo-600 hover:bg-indigo-700 border-indigo-700
                        focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-0">
                        Duplicar ahora
                    </button>
                    </div>
                </form>
                </div>
            </div>
            </div>


            {{-- Students --}}
            <div class="bg-white dark:bg-neutral-900 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-neutral-700">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-lg text-gray-800 dark:text-neutral-100">Alumnos del Grupo</h3>
                        <p class="text-sm text-gray-500 dark:text-neutral-400">Lista completa de tutorados</p>
                    </div>
                    <button type="button"
                        onclick="window.location.href='{{ route('alumnos.create', ['grupo' => $grupo->pk_grupo]) }}'"
                        class="flex items-center space-x-2 px-4 py-2 text-sm font-medium text-white
                                bg-[#2B8A7F] border border-transparent rounded-lg shadow-sm
                                hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2
                                focus:ring-teal-500 mt-3 md:mt-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Agregar Alumno</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-neutral-300">
                        <thead class="bg-[#2B8A7F] text-xs text-white uppercase">
                            <tr>
                                <th class="px-6 py-3">Nombre</th>
                                <th class="px-6 py-3">Correo</th>
                                <th class="px-6 py-3">Estatus</th>
                                <th class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($grupo->alumnos as $a)
                                <tr class="bg-white dark:bg-neutral-900 border-b dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-800">
                                <th class="px-6 py-4 font-medium text-gray-900 dark:text-neutral-100 whitespace-nowrap">
                                    {{ trim(($a->nombre ?? '').' '.($a->apellido_paterno ?? '').' '.($a->apellido_materno ?? '')) ?: 'Sin nombre' }}
                                </th>
                                <td class="px-6 py-4">{{ $a->correo ?? '—' }}</td>
                                <td class="px-6 py-4">
                                    @if(($a->estatus ?? '') === 'Activo')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">Activo</span>
                                    @elseif(($a->estatus ?? '') === 'Baja')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200">Baja</span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200">{{ $a->estatus ?? '—' }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('detalle_alumno', $a->pk_alumno) }}"
                                    class="text-gray-700 dark:text-neutral-300 hover:text-gray-900 dark:hover:text-neutral-100 text-sm font-medium transition-colors">
                                    Ver Perfil
                                    </a>
                                </td>
                                </tr>
                            @empty
                                <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-gray-500 dark:text-neutral-400">
                                    No hay alumnos en este grupo.
                                </td>
                                </tr>
                            @endforelse
                    </tbody>

                    </table>
                </div>
            </div>
        </main>
    </div>
    <script>
  (function () {
    // ...tu código de otros modales...

    // ---- Modal Duplicar Grupo ----
    const dupModal   = document.getElementById('duplicateModal');
    const dupOverlay = document.getElementById('duplicateOverlay');
    const openDup    = document.getElementById('openDuplicateModal');
    const closeDup   = document.getElementById('closeDuplicateModal');
    const cancelDup  = document.getElementById('cancelDuplicateModal');

    const openDupFn  = () => dupModal && dupModal.classList.remove('hidden');
    const closeDupFn = () => dupModal && dupModal.classList.add('hidden');

    if (openDup)   openDup.addEventListener('click', openDupFn);
    if (dupOverlay) dupOverlay.addEventListener('click', closeDupFn);
    if (closeDup)  closeDup.addEventListener('click', closeDupFn);
    if (cancelDup) cancelDup.addEventListener('click', closeDupFn);

    // Cerrar con ESC (ya cierras otros; añadimos este)
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeDupFn();
    });
  })();
</script>
</x-layouts.app>