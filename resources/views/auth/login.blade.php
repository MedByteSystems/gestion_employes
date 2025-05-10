<script src="https://cdn.tailwindcss.com"></script>
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Logo de l'entreprise -->
        <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-md bg-blue-600 text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">Portail Entreprise</h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Système de Gestion des Employés
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-lg sm:rounded-lg sm:px-10 border border-gray-100">
            @if (session('error'))
                <div class="mb-4 text-red-600 text-sm text-center">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Champ Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Professionnel</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="username" required
                            value="{{ old('email') }}" autofocus
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                            placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="nom@entreprise.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Champ Mot de passe -->
                <div>
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                Mot de passe oublié ?
                            </a>
                        @endif
                    </div>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required
                            class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm 
                            placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Case à cocher "Rester connecté" -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" 
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Rester connecté
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium 
                        text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500
                        transition duration-200">
                        Se connecter
                    </button>
                </div>
            </form>

            <!-- Message de sécurité -->
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Accès Sécurisé par Rôle</span>
                    </div>
                </div>
                <p class="mt-2 text-center text-xs text-gray-500">
                    Accès strictement réservé aux employés autorisés.<br>
                    Niveaux d'accès :
                    <span class="font-semibold">Admin</span> | 
                    <span class="font-semibold">Manager</span> | 
                    <span class="font-semibold">Employé</span>
                </p>
            </div>
        </div>
    </div>
</div>