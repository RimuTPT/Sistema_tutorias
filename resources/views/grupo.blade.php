<x-layouts.app :title="__('Tutorados y Grupos')">
    <div class="flex h-screen overflow-hidden">
        <main class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-[#262626] overflow-y-auto">

            <!-- Header -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800 dark:text-neutral-100">Tutorados y Grupos</h1>
                    <p class="text-sm text-gray-500 dark:text-neutral-400 mt-1">Gestiona grupos, alumnos y observaciones</p>
                </div>
                 <div class="flex items-center space-x-3 mt-4 md:mt-0">
                    <a href="{{ url('crear_grupo') }}"
                       class="flex items-center space-x-2 px-4 py-2 text-sm font-medium text-white bg-[#2B8A7F] border border-transparent rounded-lg shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Nuevo Grupo</span>
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-neutral-900 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-neutral-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">Grupos Actuales</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-neutral-100 mt-2">{{ $gruposActuales }}</p>
                    <p class="text-sm text-gray-400 dark:text-neutral-500 mt-1">Activos este cuatrimestre</p>
                </div>
                <div class="bg-white dark:bg-neutral-900 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-neutral-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">Total Tutorados</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-neutral-100 mt-2">{{ $totalTutorados }}</p>
                    <p class="text-sm text-gray-400 dark:text-neutral-500 mt-1">En grupos actuales</p>
                </div>
                <div class="bg-white dark:bg-neutral-900 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-neutral-700">
                    <p class="text-sm font-medium text-gray-500 dark:text-neutral-400">Grupos Anteriores</p>
                    <p class="text-3xl font-bold text-gray-800 dark:text-neutral-100 mt-2">{{ $gruposAnteriores }}</p>
                    <p class="text-sm text-gray-400 dark:text-neutral-500 mt-1">Histórico completo</p>
                </div>
            </div>

            <!-- Groups Table -->
            <div class="bg-white dark:bg-neutral-900 p-6 rounded-lg shadow-sm border border-gray-200 dark:border-neutral-700">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-lg text-gray-800 dark:text-neutral-100">Grupos Registrados</h3>
                        <p class="text-sm text-gray-500 dark:text-neutral-400">Administra tus grupos por cuatrimestre</p>
                    </div>
                    @if (Route::has('grupos.export'))
  <a href="{{ route('grupos.export') }}"
     class="flex items-center space-x-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-700 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-neutral-700">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
    </svg>
    <span>Exportar a CSV</span>
  </a>
@endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-600 dark:text-neutral-300">
                        <thead class="bg-[#2B8A7F] text-xs text-white uppercase">
                            <tr>
                                <th class="px-6 py-3">Grupo</th>
                                <th class="px-6 py-3">Cuatrimestre</th>
                                <th class="px-6 py-3">Alumnos</th>
                                <th class="px-6 py-3">Estatus</th>
                                <th class="px-6 py-3 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($grupos as $g)
                                <tr class="bg-white dark:bg-neutral-900 border-b dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-800">
                                    <th class="px-6 py-4 font-medium text-gray-900 dark:text-neutral-100 whitespace-nowrap">
                                        {{ $g->nombre_grupo }}
                                    </th>
                                    <td class="px-6 py-4">{{ $g->cuatrimestre }}</td>
                                    <td class="px-6 py-4">{{ $g->alumnos_count }}</td>
                                    <td class="px-6 py-4">
                                        @if($g->estatus === 'Activo')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-900 dark:bg-gray-100 text-white dark:text-gray-900">
                                                Actual
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-gray-200 dark:bg-neutral-700 text-gray-900 dark:text-neutral-100">
                                                Anterior
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('detalle_grupo', $g->pk_grupo) }}"
                                           class="text-gray-700 dark:text-neutral-300 hover:text-gray-900 dark:hover:text-neutral-100 text-sm font-medium transition-colors">
                                            Ver Detalles
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-6 text-center text-gray-500 dark:text-neutral-400">
                                        No hay grupos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $grupos->links() }}
                </div>
            </div>
        </main>
    </div>
</x-layouts.app>
