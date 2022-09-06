<x-app-layout title="User">
    <div x-data="app()" x-cloak>
        <div class="container grid px-6 mx-auto ">
            <div class="w-full">
                <h1 class="mt-8 mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
                    Editar usuario
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

                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Name</span>
                        <input
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="Jane Doe" type="text" name="name" value=" {{ $user->name }} " />
                    </label>

                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Email</span>
                        <input
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="test@example.com" type="email" name="email" value=" {{ $user->email }} " />
                    </label>

                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Password</span>
                        <input
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="***************" type="password" name="password" />
                    </label>

                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">User Infor</span>
                        <input
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="YKMS000" type="infor" name="infor" value=" {{ $user->infor }} "/>
                    </label>

                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">
                            Select a Role
                        </span>
                        <select name="role_id"
                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                            x-model="role">
                            @foreach ($roles as $role)
                                <option {{ $user->role_id == $role->id ? "selected" : "" }} value="{{ $role->id }}"> {{ $role->name }} </option>
                            @endforeach
                        </select>
                    </label>

                    <!-- You should use a button here, as the anchor is only used for the example  -->
                    <button
                        class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                        type="submit">
                        {{ __('Guardar') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script>
        function app() {
            return {
                role: 1,
            }
        }
    </script>
</x-app-layout>
