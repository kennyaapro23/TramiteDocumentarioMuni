@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Solicitar: {{ $tipoTramite->nombre }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('ciudadano.tramites.index') }}">Trámites</a></li>
                        <li class="breadcrumb-item active">Solicitar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-triangle me-2"></i>¡Atención!</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Datos del Trámite</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('ciudadano.tramites.store') }}" method="POST" enctype="multipart/form-data" id="formTramite">
                        @csrf
                        <input type="hidden" name="tipo_tramite_id" value="{{ $tipoTramite->id }}">

                        <div class="mb-4">
                            <label for="asunto" class="form-label fw-bold">
                                Asunto <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('asunto') is-invalid @enderror" 
                                   id="asunto" 
                                   name="asunto" 
                                   value="{{ old('asunto') }}"
                                   placeholder="Ingrese el asunto de su solicitud"
                                   maxlength="500"
                                   required>
                            @error('asunto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Breve descripción del trámite que desea realizar (máximo 500 caracteres)
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="form-label fw-bold">
                                Descripción Detallada <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" 
                                      name="descripcion" 
                                      rows="5"
                                      placeholder="Proporcione una descripción detallada de su solicitud"
                                      required>{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Explique con detalle el motivo y las circunstancias de su solicitud
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="documentos" class="form-label fw-bold">
                                Documentos Adjuntos <span class="text-danger">*</span>
                            </label>
                            <input type="file" 
                                   class="form-control @error('documentos.*') is-invalid @enderror" 
                                   id="documentos" 
                                   name="documentos[]" 
                                   multiple
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                   required>
                            @error('documentos.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Formatos permitidos: PDF, JPG, PNG, DOC, DOCX. Tamaño máximo por archivo: 10MB
                            </div>
                            <div id="fileList" class="mt-2"></div>
                        </div>

                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i>Antes de enviar su solicitud</h6>
                            <ul class="mb-0 small">
                                <li>Verifique que todos los datos sean correctos</li>
                                <li>Asegúrese de haber adjuntado todos los documentos requeridos</li>
                                <li>Revise que los archivos sean legibles y estén completos</li>
                                <li>Una vez enviada la solicitud, recibirá un número de expediente para hacer seguimiento</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('ciudadano.tramites.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-paper-plane me-2"></i>Enviar Solicitud
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Información del Trámite -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información del Trámite</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Código:</dt>
                        <dd class="col-sm-7"><span class="badge bg-primary">{{ $tipoTramite->codigo }}</span></dd>
                        
                        <dt class="col-sm-5">Gerencia:</dt>
                        <dd class="col-sm-7">{{ $tipoTramite->gerencia->nombre ?? 'N/A' }}</dd>
                        
                        <dt class="col-sm-5">Tiempo:</dt>
                        <dd class="col-sm-7"><span class="badge bg-info">{{ $tipoTramite->tiempo_estimado_dias }} días</span></dd>
                        
                        <dt class="col-sm-5">Costo:</dt>
                        <dd class="col-sm-7 mb-0"><span class="text-success fw-bold">S/. {{ number_format($tipoTramite->costo, 2) }}</span></dd>
                    </dl>
                </div>
            </div>

            <!-- Documentos Requeridos -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-file-pdf me-2"></i>Documentos Requeridos</h6>
                </div>
                <div class="card-body">
                    @if($tipoTramite->documentos->count() > 0)
                        <ul class="list-unstyled mb-0">
                            @foreach($tipoTramite->documentos as $documento)
                            <li class="mb-2">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong>{{ $documento->nombre }}</strong>
                                <br>
                                <small class="text-muted ms-4">{{ $documento->descripcion }}</small>
                            </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            No se han especificado documentos obligatorios
                        </p>
                    @endif
                </div>
            </div>

            @if($tipoTramite->requiere_pago)
            <div class="card mt-3">
                <div class="card-body bg-light-warning">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-warning fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Requiere Pago</h6>
                            <p class="text-muted small mb-0">
                                Este trámite requiere el pago de una tasa antes de ser procesado.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('documentos');
    const fileList = document.getElementById('fileList');
    
    fileInput.addEventListener('change', function(e) {
        fileList.innerHTML = '';
        
        if (this.files.length > 0) {
            const div = document.createElement('div');
            div.className = 'card';
            
            let html = '<div class="card-header py-2 bg-light"><small class="fw-bold">Archivos seleccionados:</small></div><ul class="list-group list-group-flush">';
            
            Array.from(this.files).forEach((file, index) => {
                const size = (file.size / 1024 / 1024).toFixed(2);
                const icon = getFileIcon(file.name);
                html += `
                    <li class="list-group-item py-2 small">
                        <i class="${icon} me-2"></i>
                        <strong>${file.name}</strong>
                        <span class="text-muted float-end">${size} MB</span>
                    </li>
                `;
            });
            
            html += '</ul>';
            div.innerHTML = html;
            fileList.appendChild(div);
        }
    });
    
    function getFileIcon(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        switch(ext) {
            case 'pdf': return 'fas fa-file-pdf text-danger';
            case 'doc':
            case 'docx': return 'fas fa-file-word text-primary';
            case 'jpg':
            case 'jpeg':
            case 'png': return 'fas fa-file-image text-success';
            default: return 'fas fa-file';
        }
    }
});
</script>
@endsection
