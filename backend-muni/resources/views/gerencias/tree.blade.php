@forelse($gerencias as $gerencia)
<div class="tree-item {{ $gerencia->tipo }}" data-id="{{ $gerencia->id }}">
    <div class="tree-item-header">
        <div class="tree-item-title">
            {{ $gerencia->nombre }}
            <span class="badge bg-{{ $gerencia->tipo == 'gerencia' ? 'primary' : ($gerencia->tipo == 'subgerencia' ? 'info' : 'secondary') }} ms-2">
                {{ ucfirst($gerencia->tipo) }}
            </span>
            <span class="badge bg-{{ $gerencia->estado == 'activo' ? 'success' : 'danger' }} ms-1">
                {{ ucfirst($gerencia->estado) }}
            </span>
        </div>
        <div class="btn-group" role="group">
            <button class="btn btn-sm btn-outline-primary" onclick="editGerencia({{ $gerencia->id }})" title="Editar">
                <i class="fas fa-edit"></i>
            </button>
            <button class="btn btn-sm btn-outline-info" onclick="viewGerencia({{ $gerencia->id }})" title="Ver detalles">
                <i class="fas fa-eye"></i>
            </button>
            <button class="btn btn-sm btn-outline-success" onclick="addSubGerencia({{ $gerencia->id }})" title="Agregar subgerencia">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    
    <div class="tree-item-info">
        <strong>Código:</strong> {{ $gerencia->codigo }} |
        <strong>Usuarios:</strong> {{ $gerencia->usuarios_count }} |
        @if($gerencia->responsable)
        <strong>Responsable:</strong> {{ $gerencia->responsable->name }}
        @else
        <strong>Responsable:</strong> Sin asignar
        @endif
    </div>
    
    @if($gerencia->descripcion)
    <div class="tree-item-info mt-1">
        <small>{{ $gerencia->descripcion }}</small>
    </div>
    @endif

    @if($gerencia->children && $gerencia->children->count() > 0)
    <div class="tree-children">
        @include('gerencias.tree', ['gerencias' => $gerencia->children])
    </div>
    @endif
</div>
@empty
<div class="text-center py-4">
    <i class="fas fa-building fa-3x text-muted mb-3 d-block"></i>
    <p class="text-muted">No hay gerencias para mostrar</p>
</div>
@endforelse