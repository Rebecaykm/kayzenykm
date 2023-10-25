<x-app-layout title="Editar Rol">
    <div class="container grid px-6 mx-auto overflow-y-auto">
        <div class="w-full">
            <h1 class="mt-8 mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
                Editar Rol
            </h1>

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

            <form method="POST" action="{{ route('roles.update', $role->id) }}">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-6 gap-4 px-4 py-2 rounded-lg shadow-xs border-1 bg-white dark:bg-gray-80">
                    <div class="col-span-6">
                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Nombre del Rol</span>
                            <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Enter the role name" type="text" name="name" value="{{ $role->name }}" />
                        </label>
                    </div>
                    @foreach ($permissions as $key => $permission)
                    <div class="flex col-span-2 p-2 text-sm">
                        <label class="flex items-center dark:text-gray-400">
                            <input type="checkbox" value="{{ $permission->id }}" name="permissions[]" class="text-blue-600 form-checkbox focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray" @if (isset($role->permissions))
                            @foreach ($role->permissions as $i => $role_permissions)
                            @if ($role_permissions->id == $permission->id)
                            {{ "checked" }}
                            @endif
                            @endforeach
                            @endif
                            />
                            <span class="ml-2">
                                {{ $permission->description }}
                            </span>
                        </label>
                    </div>
                    @endforeach
                </div>
                <div class="flex justify-end mt-4">
                    <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue" type="submit">
                        <span>{{ __('Guardar')}}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
