<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Modifier Département</h2>
                        <a href="{{ route('Admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">
                            ← Retour au tableau de bord
                        </a>
                    </div>

                    <form action="{{ route('Admin.departements.update', $departement->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="max-w-xl space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nom du département
                                </label>
                                <input type="text" name="name" id="name" 
                                    class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                    value="{{ old('name', $departement->name) }}" required>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex justify-end space-x-4">
                                <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                    Mettre à jour
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>