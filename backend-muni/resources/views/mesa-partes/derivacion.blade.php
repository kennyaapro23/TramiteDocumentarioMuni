@extends('layouts.app')
@section('title','Derivación - Mesa de Partes')
@section('content')
<div class="py-6"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8"><div class="bg-white p-6 rounded shadow">
    <h3 class="text-lg font-medium">Derivar expediente</h3>
    <p class="text-sm text-gray-500">Formulario para derivar un expediente a una gerencia.</p>
    <form method="POST" action="#" class="mt-4">
        @csrf
        <div class="mb-3"><label>Expediente ID</label><input name="expediente_id" class="w-full border rounded px-3 py-2"></div>
        <div class="mb-3"><label>Gerencia destino</label><select name="gerencia_id" class="w-full border rounded px-3 py-2">@foreach(\App\Models\Gerencia::activo()->get() as $g)<option value="{{ $g->id }}">{{ $g->nombre }}</option>@endforeach</select></div>
        <div class="flex gap-2"><button class="px-4 py-2 bg-green-600 text-white rounded">Derivar</button><a href="{{ route('mesa-partes.index') }}" class="px-4 py-2 bg-gray-100 rounded">Cancelar</a></div>
    </form>
</div></div></div>
@endsection
