@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Encabezado -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-red-500 mb-2">Crear Nuevo Objeto</h1>
            <p class="text-gray-600">Completa la información de tu objeto para intercambiar</p>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-xl shadow-lg border border-red-100 mx-auto px-8 pb-8">
            <form action="{{ route('garaje.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Columna Izquierda: Campos principales -->
                    <div class="space-y-6">
                        <!-- Título -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                Título del objeto *
                            </label>
                            <input type="text" id="title" name="title"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                placeholder="Ej: iPhone 13 Pro en excelente estado" value="{{ old('title') }}" required>
                            @error('title')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Descripción *
                            </label>
                            <textarea id="description" name="description" rows="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none"
                                placeholder="Describe tu objeto, su estado, características especiales, etc."
                                required>{{ old('description') }}</textarea>
                            @error('description')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Categoría y Condición (juntos en desktop, separados en móvil) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Categoría -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                    Categoría *
                                </label>
                                <select id="category" name="category"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                    required>
                                    <option value="">Selecciona una categoría</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Condición -->
                            <div>
                                <label for="condition" class="block text-sm font-medium text-gray-700 mb-2">
                                    Condición *
                                </label>
                                <select id="condition" name="condition"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors"
                                    required>
                                    <option value="">Selecciona condición</option>
                                    @foreach ($conditions as $key => $label)
                                        <option value="{{ $key }}" {{ old('condition') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('condition')
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Preferencias de intercambio -->
                        <div>
                            <label for="exchange_preferences" class="block text-sm font-medium text-gray-700 mb-2">
                                Preferencias de intercambio
                            </label>
                            <textarea id="exchange_preferences" name="exchange_preferences" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors resize-none"
                                placeholder="¿Qué tipo de objetos te interesan a cambio? Ej: Libros de ciencia ficción, videojuegos, etc.">{{ old('exchange_preferences') }}</textarea>
                            @error('exchange_preferences')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Columna Derecha: Área de fotos -->
                    <div class="space-y-6">
                        <!-- Área de subida de fotos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Fotos del objeto *
                            </label>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-red-400 transition-colors cursor-pointer bg-gray-50 hover:bg-gray-100">
                                <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="hidden"
                                    required>
                                <label for="photos" class="cursor-pointer">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-camera text-4xl text-gray-400 mb-4"></i>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Sube tus fotos</h3>
                                        <p class="text-sm text-gray-600 mb-2">Arrastra y suelta tus fotos aquí</p>
                                        <p class="text-xs text-gray-500">o haz clic para seleccionar archivos</p>
                                        <p class="text-xs text-red-500 mt-2">(máximo 5 fotos)*</p>
                                    </div>
                                </label>
                            </div>
                            <div id="photo-error" class="text-red-500 text-sm mt-2"></div>
                            @error('photos')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                            @error('photos.*')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Área de miniaturas (carrusel visual) -->
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Vista previa de fotos</h4>
                            <div id="preview" class="flex space-x-3 overflow-x-auto pb-2"></div>
                            <p class="text-xs text-gray-500 mt-2">Las fotos aparecerán aquí una vez seleccionadas</p>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción -->
                <div class="flex flex-col sm:flex-row justify-between items-center pt-8 border-t border-gray-200 gap-4">
                    <a href="{{ route('garaje.index') }}"
                        class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-white bg-gray-500 rounded-lg hover:bg-gray-600 transition-colors flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver a mis objetos
                    </a>

                    <button type="submit"
                        class="w-full sm:w-auto px-8 py-3 bg-red-500 text-white rounded-lg hover:bg-red-700 transition-colors flex items-center justify-center font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Crear Objeto
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const input = document.getElementById('photos');
            const preview = document.getElementById('preview');
            const errorDiv = document.getElementById('photo-error');
            let filesArray = [];

            input.addEventListener('change', function (event) {
                let selectedFiles = Array.from(event.target.files);

                filesArray = filesArray.concat(selectedFiles);

                filesArray = filesArray.filter((file, index, self) =>
                    index === self.findIndex(f => (
                        f.name === file.name && f.lastModified === file.lastModified
                    ))
                );

                if (filesArray.length > 5) {
                    errorDiv.textContent = "Solo puedes subir hasta 5 imágenes.";
                    filesArray = filesArray.slice(0, 5);
                } else {
                    errorDiv.textContent = "";
                }

                updateInputFiles();
                renderPreviews();
            });

            function renderPreviews() {
                preview.innerHTML = '';
                filesArray.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const container = document.createElement('div');
                        container.className = "relative flex-shrink-0 w-24 h-24 bg-gray-200 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden";

                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = "object-cover w-full h-full";

                        const btn = document.createElement('button');
                        btn.type = "button";
                        btn.innerHTML = "&#10005;";
                        btn.className = "absolute top-1 right-1 bg-red-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-800";
                        btn.onclick = () => {
                            filesArray.splice(index, 1);
                            updateInputFiles();
                            renderPreviews();
                            errorDiv.textContent = "";
                        };

                        container.appendChild(img);
                        container.appendChild(btn);
                        preview.appendChild(container);
                    };
                    reader.readAsDataURL(file);
                });
            }

            function updateInputFiles() {
                const dataTransfer = new DataTransfer();
                filesArray.forEach(file => dataTransfer.items.add(file));
                input.files = dataTransfer.files;
            }
        });
    </script>
@endpush