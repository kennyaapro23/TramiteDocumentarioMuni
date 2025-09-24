@extends('layouts.app')
@section('title','Registro - Mesa de Partes')
@section('content')
<div class="py-6"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><div class="bg-white p-6 rounded shadow">
    <h3 class="text-lg font-medium">Registro de documentos</h3>
    <p class="text-sm text-gray-500">Formulario simple para registrar un expediente desde Mesa de Partes.</p>
    <form method="POST" action="{{ route('expedientes.store') }}" enctype="multipart/form-data" class="mt-4">
        @csrf
        <div class="mb-3"><label>Solicitante</label><input name="solicitante_nombre" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label>Asunto</label><input name="asunto" class="w-full border rounded px-3 py-2" required></div>
        <div class="mb-3"><label>Gerencia</label><select name="gerencia_id" class="w-full border rounded px-3 py-2">
            @foreach(\App\Models\Gerencia::where('activo', true)->get() as $g)
                <option value="{{ $g->id }}">{{ $g->nombre }}</option>
            @endforeach
        </select></div>
        <div class="mb-3"><label>Documento (1)</label><input type="file" name="documentos[0][archivo]" class="w-full"></div>
        <div class="flex gap-2"><button class="px-4 py-2 bg-blue-600 text-white rounded">Registrar</button><a href="{{ route('mesa-partes.index') }}" class="px-4 py-2 bg-gray-100 rounded">Cancelar</a></div>
    </form>
</div></div></div>
@endsection
