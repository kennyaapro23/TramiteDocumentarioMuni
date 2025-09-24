@extends('layouts.app')

@section('title', 'Expediente')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Expediente {{ $expediente->numero ?? $expediente->id }}</h3>
                    <p class="text-sm text-gray-500">Creado: {{ $expediente->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <a href="{{ route('expedientes.index') }}" class="px-3 py-2 bg-gray-100 rounded">Volver</a>
                </div>
            </div>

            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dt class="text-xs text-gray-500">Asunto</dt>
                    <dd class="text-sm text-gray-900">{{ $expediente->asunto }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Solicitante</dt>
                    <dd class="text-sm text-gray-900">{{ $expediente->solicitante_nombre }} ({{ $expediente->solicitante_dni }})</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Gerencia</dt>
                    <dd class="text-sm text-gray-900">{{ $expediente->gerencia->nombre ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-xs text-gray-500">Estado</dt>
                    <dd class="text-sm text-gray-900">{{ \App\Models\Expediente::getEstados()[$expediente->estado] ?? $expediente->estado }}</dd>
                </div>
            </dl>

            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-700">Documentos</h4>
                <ul class="mt-2 space-y-2">
                    @forelse($expediente->documentos as $doc)
                        <li class="flex items-center justify-between bg-gray-50 p-3 rounded">
                            <div>
                                <div class="font-medium">{{ $doc->nombre }}</div>
                                <div class="text-xs text-gray-500">{{ $doc->extension }} • {{ number_format($doc->tamao/1024, 2) ?? '' }} KB</div>
                            </div>
                            <div>
                                <a href="#" class="text-blue-600">Descargar</a>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-gray-500">No hay documentos.</li>
                    @endforelse
                </ul>
            </div>

            <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-700">Historial</h4>
                <ul class="mt-2 space-y-2">
                    @foreach($expediente->historial as $h)
                        <li class="bg-white p-3 rounded border">
                            <div class="text-sm text-gray-700">{{ $h->accion }} <span class="text-xs text-gray-400">- {{ $h->created_at->diffForHumans() }}</span></div>
                            <div class="text-xs text-gray-500">{{ $h->observaciones }}</div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
