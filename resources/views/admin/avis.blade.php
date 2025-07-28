@extends('admin.dashboard')

@section('title', 'Gestion des Avis')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Gestion des Avis</h1>
        
        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="bg-gray-100 text-gray-700 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">ID</th>
                        <th class="py-3 px-6 text-left">Nom Utilisateur</th>
                        <th class="py-3 px-6 text-left">Note</th>
                        <th class="py-3 px-6 text-left">Commentaire</th>
                        <th class="py-3 px-6 text-left">État</th>
                        <th class="py-3 px-6 text-left">Date</th>
                        <th class="py-3 px-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @forelse($avis as $a)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-6 text-left">{{ $a->id }}</td>
                        <td class="py-3 px-6 text-left">{{ $a->nom_utilisateur }}</td>
                        <td class="py-3 px-6 text-left">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $a->note)
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endif
                                @endfor
                                <span class="ml-1 text-gray-600 text-sm">({{ $a->note }}/5)</span>
                            </div>
                        </td>
                        <td class="py-3 px-6 text-left">{{ Str::limit($a->commentaire, 50) }}</td>
                        <td class="py-3 px-6 text-left">
                            <span class="px-2 py-1 rounded-full text-xs {{ $a->etat == 'visible' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $a->etat == 'visible' ? 'Visible' : 'Non visible' }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-left">{{ $a->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-3 px-6 text-center">
                            <div class="flex item-center justify-center space-x-2">
                                @if($a->etat == 'non_visible')
                                <form action="{{ route('admin.avis.update', $a) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="etat" value="visible">
                                    <button type="submit" class="text-blue-600 hover:text-blue-900" title="Rendre visible">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.avis.update', $a) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="etat" value="non_visible">
                                    <button type="submit" class="text-gray-600 hover:text-gray-900" title="Masquer">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </form>
                                @endif
                                
                                <form action="{{ route('admin.avis.destroy', $a) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet avis?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-6 text-center">Aucun avis disponible</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
