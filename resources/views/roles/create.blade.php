<x-app-layout title="Create Role">
    <div class="container grid px-6 mx-auto overflow-y-auto">
        <div class="w-full">
            <h1 class="mt-8 mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
                Create User
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

            <form method="POST" action="{{ route('roles.store') }}">
                @csrf
                <div class="grid grid-cols-6 gap-4 px-4 py-2 rounded-lg shadow-xs border-1 bg-white dark:bg-gray-80">
                    <div class="col-span-6">
                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Name</span>
                            <input class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" placeholder="Enter the role name" type="text" name="name" required autofocus />
                        </label>
                    </div>
                    @foreach ($permissions as $permission)
                    <div class="flex col-span-2 p-2 text-sm">
                        <label class="flex items-center dark:text-gray-400">
                            <input type="checkbox" value="{{ $permission->id }}" name="permissions[]" class="text-blue-600 form-checkbox focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray" />
                            <span class="ml-2">
                                {{ $permission->description }}
                            </span>
                        </label>
                    </div>
                    @endforeach
                    <div class="col-span-6">
                        <button class="block w-full px-4 py-2 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue" type="submit">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
