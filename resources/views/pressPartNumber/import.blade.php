<x-app-layout title="{{ __('Números de Parte de Prensas') }}">
    <div class="xl:container lg:container md:container sm:container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Números de Parte de Prensas') }}
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

        <div class="py-2">
            @if (session('warning'))
            <div class="bg-orange-100 border border-orange-400 text-orange-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{!! session('warning') !!}</span>
            </div>
            @endif

            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{!! session('error') !!}</span>
            </div>
            @endif
        </div>

        <div class="px-4 py-3 mb-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form action="{{ route('press-part-number.import') }}" method="post" enctype="multipart/form-data">
                @csrf
                <label class="block text-sm">
                    <div class="relative text-gray-500 focus-within:text-purple-600">
                        <input type="file" name="file" accept=".csv,.xlsx" class="block w-full pr-20 mt-1 text-sm text-black dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input" />
                        <button type="submit" class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-r-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Cargar
                        </button>
                    </div>
                </label>
            </form>

            <div class="block mt-4">
                <a href="{{ route('press-part-number.index') }}">
                    <button class="w-full md:w-auto lg:w-auto px-10 py-2 font-semibold leading-tight text-gray-700 transition-colors duration-150 bg-gray-200 border border-transparent rounded-lg active:bg-gray-200 hover:bg-gray-200 focus:outline-none focus:shadow-outline-gray dark:text-white dark:bg-gray-500">
                        <div class="flex justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                            </svg>
                            <span class="ml-2">{{ __('Regresar') }}</span>
                        </div>
                    </button>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
