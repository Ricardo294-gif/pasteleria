@extends('admin.layouts.admin')

@section('title', 'Editar Producto')

@section('content')
    <style>
    /* Estilos para alertas flotantes */
    .floating-alerts {
            position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        }

    .floating-alert {
            margin-bottom: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border: none;
        opacity: 0;
        transform: translateX(100%);
        animation: slideIn 0.5s ease forwards;
        }

    @keyframes slideIn {
        to {
            opacity: 1;
                transform: translateX(0);
            }
        }

        .form-label {
            font-weight: 500;
        color: #333;
        }

    .form-control:focus, .form-select:focus {
        border-color: #ff7070;
        box-shadow: 0 0 0 0.2rem rgba(255, 112, 112, 0.25);
        }

    .btn-primary {
        background-color: #ff7070;
        border-color: #ff7070;
        }

    .btn-primary:hover {
        background-color: #ff5656;
        border-color: #ff5656;
        }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
        }
    </style>

<div class="container-fluid">
    <!-- Encabezado -->
    <div class="page-header">
        <h1 class="page-title">Editar Producto</h1>
        <p class="text-muted">Modifica los detalles del producto existente.</p>
    </div>

    <div class="floating-alerts">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show floating-alert" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show floating-alert" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Formulario -->
                <div class="card">
                    <div class="card-header">
            <i class="bi bi-pencil-square me-2"></i>Información del Producto
                    </div>
                    <div class="card-body">
            <form action="{{ route('admin.productos.actualizar', $producto->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            @method('PUT')
                
                <div class="row">
                                <div class="col-md-6">
                        <!-- Nombre del producto -->
                                    <div class="mb-4">
                                        <label for="nombre" class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                                    </div>

                        <!-- Precio -->
                                    <div class="mb-4">
                                        <label for="precio" class="form-label">Precio (€) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('precio') is-invalid @enderror" 
                                   id="precio" name="precio" value="{{ old('precio', $producto->precio) }}" step="0.01" required>
                            @error('precio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                                    </div>

                        <!-- Categoría -->
                                    <div class="mb-4">
                                        <label for="categoria_id" class="form-label">Categoría</label>
                            <select class="form-select @error('categoria_id') is-invalid @enderror" 
                                    id="categoria_id" name="categoria_id">
                                <option value="">Selecciona una categoría</option>
                                            @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" 
                                        {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                            @endforeach
                                        </select>
                            @error('categoria_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                                    </div>

                        <!-- Ingredientes -->
                                    <div class="mb-4">
                                        <label for="ingredientes" class="form-label">Ingredientes <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('ingredientes') is-invalid @enderror" 
                                      id="ingredientes" name="ingredientes" rows="3" required>{{ old('ingredientes', $producto->ingredientes) }}</textarea>
                            @error('ingredientes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                        <!-- Descripción corta -->
                                    <div class="mb-4">
                                        <label for="descripcion" class="form-label">Descripción Corta <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="3" required>{{ old('descripcion', $producto->descripcion) }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                                    </div>

                        <!-- Descripción larga -->
                                    <div class="mb-4">
                            <label for="descripcion_larga" class="form-label">Descripción Detallada</label>
                            <textarea class="form-control @error('descripcion_larga') is-invalid @enderror" 
                                      id="descripcion_larga" name="descripcion_larga" rows="4">{{ old('descripcion_larga', $producto->descripcion_larga) }}</textarea>
                            @error('descripcion_larga')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                                    </div>

                        <!-- Imagen -->
                                    <div class="mb-4">
                                        <label for="imagen" class="form-label">Imagen del Producto</label>
                            <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                                   id="imagen" name="imagen" accept="image/*">
                            <div class="form-text">Formatos permitidos: JPG, PNG. Tamaño máximo: 2MB</div>
                            @error('imagen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Vista previa de la imagen actual -->
                            @if($producto->imagen)
                                <div class="mt-2">
                                    <img src="{{ asset('img/productos/' . $producto->imagen) }}" 
                                         alt="{{ $producto->nombre }}" 
                                         class="img-thumbnail" 
                                         style="max-height: 200px;">
                                                </div>
                                            @endif
                                    </div>
                                </div>
                            </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Guardar Cambios
                                </button>
                                <a href="{{ route('admin.productos') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x me-2"></i>Cancelar
                                </a>
                            </div>
                        </form>
            </div>
        </div>
    </div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Vista previa de la imagen
            const imageInput = document.getElementById('imagen');
    const preview = document.querySelector('.img-thumbnail');
    const previewContainer = preview ? preview.parentElement : null;

                imageInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
            reader.onload = function(e) {
                if (!preview) {
                    const newPreview = document.createElement('div');
                    newPreview.className = 'mt-2';
                    newPreview.innerHTML = `
                        <img src="${e.target.result}" 
                             alt="Vista previa" 
                             class="img-thumbnail" 
                             style="max-height: 200px;">
                    `;
                    imageInput.parentElement.appendChild(newPreview);
                } else {
                    preview.src = e.target.result;
                }
            }
                        reader.readAsDataURL(file);
                    }
                });

    // Validación del formulario
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
                });
        });
    </script>
@endpush
@endsection
