<x-layouts.app :title="__('Nuevo Grupo')">
  <div class="flex h-screen overflow-hidden">
    <main class="flex-1 p-6 md:p-8 bg-slate-50 dark:bg-[#262626] overflow-y-auto">

      <!-- Header -->
      <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('grupo') }}"
           class="flex items-center space-x-2 px-3 py-2 text-sm font-medium text-gray-700 dark:text-neutral-300 hover:text-gray-900 dark:hover:text-neutral-100 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          <span>Volver</span>
        </a>
        <div>
          <h1 class="text-2xl font-bold text-gray-800 dark:text-neutral-100">Nuevo Grupo</h1>
          <p class="text-sm text-gray-500 dark:text-neutral-400">Registra un nuevo grupo de tutorados</p>
        </div>
      </div>

      <!-- Form Card -->
      <div class="bg-white dark:bg-neutral-900 rounded-lg shadow-sm border border-gray-200 dark:border-neutral-700">
        <div class="p-6 border-b border-gray-200 dark:border-neutral-700">
          <h3 class="font-bold text-lg text-gray-800 dark:text-neutral-100">Información del Grupo</h3>
          <p class="text-sm text-gray-500 dark:text-neutral-400">Completa los datos del grupo</p>
        </div>

        <div class="p-6">
          {{-- Errores de validación --}}
          @if ($errors->any())
            <div class="mb-4 rounded-lg border border-red-300 bg-red-50 dark:bg-red-900/20 p-3 text-sm text-red-700 dark:text-red-300">
              <ul class="list-disc pl-5">
                @foreach ($errors->all() as $e)
                  <li>{{ $e }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form class="space-y-6" method="POST" action="{{ route('grupos.store') }}">
            @csrf

            <!-- Datos del Grupo -->
            <div>
              <h3 class="text-base font-semibold text-gray-800 dark:text-neutral-100 mb-4">Datos del Grupo</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                {{-- Nombre del Grupo --}}
                <div>
                  <label for="nombre_grupo" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                    Nombre del Grupo *
                  </label>
                  <input type="text" id="nombre_grupo" name="nombre_grupo" required
                         value="{{ old('nombre_grupo') }}"
                         placeholder="Ej: Ingeniería en Software 5A"
                         class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-800 text-gray-900 dark:text-neutral-100 placeholder-gray-400 dark:placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                </div>

                {{-- Cuatrimestre --}}
                <div>
                  <label for="cuatrimestre" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                    Cuatrimestre
                  </label>
                  <select id="cuatrimestre" name="cuatrimestre"
                          class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-800 text-gray-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                    <option value="">Seleccionar...</option>
                    <option value="ene-abr-2025" @selected(old('cuatrimestre')==='ene-abr-2025')>Enero-Abril 2025</option>
                    <option value="may-ago-2025" @selected(old('cuatrimestre')==='may-ago-2025')>Mayo-Agosto 2025</option>
                    <option value="sep-dic-2025" @selected(old('cuatrimestre')==='sep-dic-2025')>Septiembre-Diciembre 2025</option>
                  </select>
                </div>

                {{-- Estatus --}}
                <div class="md:col-span-2">
                  <label for="estatus" class="block text-sm font-medium text-gray-700 dark:text-neutral-300 mb-2">
                    Estatus *
                  </label>
                  <select id="estatus" name="estatus" required
                          class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-neutral-600 rounded-lg bg-white dark:bg-neutral-800 text-gray-900 dark:text-neutral-100 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors">
                    <option value="Activo"   @selected(old('estatus','Activo')==='Activo')>Activo</option>
                    <option value="Inactivo" @selected(old('estatus')==='Inactivo')>Inactivo</option>
                  </select>
                </div>

              </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-3 justify-end pt-4">
              <a href="{{ route('grupo') }}"
                 class="flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-neutral-200 bg-white dark:bg-neutral-800 border border-gray-300 dark:border-neutral-700 rounded-lg shadow-sm hover:bg-gray-50 dark:hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition-colors">
                Cancelar
              </a>
              <button type="submit"
                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-[#2B8A7F] border border-transparent rounded-lg shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition-colors">
                Guardar Grupo
              </button>
            </div>

          </form>
        </div>
      </div>
    </main>
  </div>
</x-layouts.app>
