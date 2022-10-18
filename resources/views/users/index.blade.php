<x-app-layout title="Usuarios">
    <div class="container grid px-3 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Usuarios
        </h2>

        @if ($errors->any())
        <div class="mb-4">
            <div class="font-medium text-red-600">¡Oh no! Algo salió mal.</div>

            <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('success'))
        <div class="mb-4 text-sm font-medium text-green-600">
            {{ session('success') }}
        </div>
        @endif

        <div class="flex justify-end mb-4">
            <a href="{{ route('users.create') }}" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                Nuevo Usuario
            </a>
        </div>
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Nombre</th>
                            <th class="px-4 py-3">Correo</th>
                            <th class="px-4 py-3">Usuario de Infor</th>
                            <th class="px-4 py-3">Fecha de Creación</th>
                            <th class="px-4 py-3">Fecha de Actualización</th>
                            <th class="px-4 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                        @foreach ($users as $user)
                        <tr class="text-gray-700 dark:text-gray-400">
                            <td class="px-4 py-3">
                                <div class="flex items-center text-xs">
                                    <!-- Avatar with inset shadow -->
                                    <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                        <img class="object-cover w-full h-full rounded-full" src="{{ $user->profile_photo_url }}" alt="" loading="lazy" />
                                        <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true">
                                        </div>
                                    </div>
                                    <div>
                                        <p class="font-semibold"></p>
                                    </div>
                                    <div>
                                        <p class="font-semibold">{{ $user->name }}</p>
                                        @if (!empty($user->getRoleNames()))
                                        @foreach ($user->getRoleNames() as $nameRole)
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ $nameRole }}
                                        </p>
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $user->infor }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $user->created_at }}
                            </td>
                            <td class="px-4 py-3 text-xs">
                                {{ $user->updated_at }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center space-x-4 text-sm">
                                    <a href="{{ route('users.edit', $user->id) }}" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Edit">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                            </path>
                                        </svg>
                                    </a>
                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-blue-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray" aria-label="Delete">
                                            <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <div <div class="grid px-4 py-3 text-xs rounded-md font-semibold tracking-wide text-gray-700 uppercase border-t dark:border-gray-700 bg-gray-100 grid-cols-9 dark:text-gray-500 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    Show {{ $users->firstItem() }} - {{ $users->lastItem() }} of {{ $users->total() }}
                </span>
                <!-- Pagination -->
                <span class="flex col-span-6 mt-2 sm:mt-auto sm:justify-end">
                    {{ $users->withQueryString()->links() }}
                </span>
            </div>
        </div>
    </div>
</x-app-layout>