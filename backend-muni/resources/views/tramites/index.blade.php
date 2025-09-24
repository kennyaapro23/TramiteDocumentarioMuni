@extends('layouts.app')

@section('title', 'Trámites')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow sm:rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium">Tipos de trámite</h3>
                <a href="{{ route('tramites.create') }}" class="px-3 py-2 bg-blue-600 text-white rounded">Nuevo</a>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-left">Nombre</th><th class="px-6 py-3"></th></tr></thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tramites as $t)
                        <tr>
                            <td class="px-6 py-4">{{ $t->nombre }}</td>
                            
                            <td class="px-6 py-4 text-right"><a href="{{ route('tramites.edit', $t->id) }}" class="text-blue-600">Editar</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">No hay trámites.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-4">{{ $tramites->links() }}</div>
        </div>
    </div>
</div>
@endsection
