<x-app-layout title="Editar Usuario">
    <div x-data="app()" x-cloak>
        <div class="container grid px-6 mx-auto ">
            <div class="w-full">
                <h1 class="mt-8 mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
                    Editar Usuario
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

                @if (session('status'))
                <div class="mb-4 text-sm font-medium text-green-600">
                    {{ session('status') }}
                </div>
                @endif

                <form method="POST" action="{{ route('users.update', $user->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Nombre</span>
                            <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Jane Doe" type="text" name="name" value=" {{ $user->name }} " />
                        </label>
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Usuario de Infor</span>
                            <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="YKMS000" type="infor" name="infor" value=" {{ $user->infor }} " />
                        </label>
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Correo</span>
                            <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="test@example.com" type="email" name="email" value=" {{ $user->email }} " />
                        </label>
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Contraseña</span>
                            <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="***************" type="password" name="password" />
                        </label>
                        <label class="block mt-4 text-sm">
                            <span class="text-gray-700 dark:text-gray-400">
                                Role
                            </span>
                            <select name="role_id" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">

                                @foreach ($roles as $key => $role)
                                <option {{ $user->role_id == $role->id ? "selected" : "" }} value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <div class="col-span-3 mt-4">
                            <span class="text-gray-700 dark:text-gray-400">
                                Departamentos
                            </span>
                            <div class="flex rounded border-2 border-gray-200 bg-white">
                                @foreach ($departaments as $i => $departament)
                                <label class="flex items-center p-2 dark:text-gray-400">
                                    <input type="checkbox" value="{{ $departament->id }}" name="departament[]" class="text-blue-600 form-checkbox focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray" @if (isset($user->departaments))
                                    @foreach ($user->departaments as $i => $user_departament)
                                    @if ($user_departament->id == $departament->id)
                                    {{ "checked" }}
                                    @endif
                                    @endforeach
                                    @endif />
                                    <span class="ml-2">
                                        {{ $departament->name }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue" type="submit">
                                <span>{{ __('Guardar')}}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- <script>
        function app() {
            return {
                role: 1,
            }
        }
    </script> -->
</x-app-layout>
