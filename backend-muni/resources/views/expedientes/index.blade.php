@extends('layouts.app')

@section('title', 'Expedientes')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Expedientes</h3>
                <a href="{{ route('expedientes.create') }}" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded">Nuevo expediente</a>
            </div>

            <form method="GET" class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por número, asunto o solicitante" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2" />
                <select name="gerencia_id" class="shadow-sm block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2">
                    <option value="">Todas las gerencias</option>
                    @foreach($gerencias as $g)
                        <option value="{{ $g->id }}" @selected(request('gerencia_id') == $g->id)>{{ $g->nombre }}</option>
                    @endforeach
                </select>
                <select name="estado" class="shadow-sm block w-full sm:text-sm border-gray-300 rounded-md px-3 py-2">
                    <option value="">Todos los estados</option>
                    @foreach(\App\Models\Expediente::getEstados() as $key => $label)
                        <option value="{{ $key }}" @selected(request('estado') == $key)>{{ $label }}</option>
                    @endforeach
                </select>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Filtrar</button>
                    <a href="{{ route('expedientes.index') }}" class="px-4 py-2 bg-gray-100 rounded">Limpiar</a>
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="p-4 bg-gray-50 rounded">
                    <div class="text-sm text-gray-500">Pendientes</div>
                    <div class="text-2xl font-bold">{{ $stats['pendientes'] ?? 0 }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded">
                    <div class="text-sm text-gray-500">En proceso</div>
                    <div class="text-2xl font-bold">{{ $stats['en_proceso'] ?? 0 }}</div>
                </div>
                <div class="p-4 bg-gray-50 rounded">
                    <div class="text-sm text-gray-500">Resueltos</div>
                    <div class="text-2xl font-bold">{{ $stats['resueltos'] ?? 0 }}</div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asunto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Solicitante</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gerencia</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($expedientes as $exp)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $exp->numero ?? $exp->numero_expediente ?? $exp->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ Str::limit($exp->asunto, 80) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $exp->solicitante_nombre ?? $exp->ciudadano_nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $exp->gerencia->nombre ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ \App\Models\Expediente::getEstados()[$exp->estado] ?? $exp->estado }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('expedientes.show', $exp->id) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No hay expedientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $expedientes->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
