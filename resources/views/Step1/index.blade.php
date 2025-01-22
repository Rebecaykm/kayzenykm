<x-app-layout title="Planeacion">
    <div class="container grid px-6 mx-auto">

        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">Búsqueda F1</h4>
        <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <div class="flex">

                <form method="post" action="{{ route('offset.store') }}">
                    @csrf
                    <div class="flex-auto">
                        <label class="block text-sm">
                            <span class="text-gray-700 dark:text-gray-400">Número de parte</span>
                            <input name="item" type="text"
                                class="block w-full mt-1 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none form-input" />
                        </label>
                    </div>
                    <div class="flex-auto ">
                        <button type="submit"
                            class=" flex items-center justify-between px-4 pt-6 py-2 mt-5 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                            <span class="mr-2">Search</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path d="M9 9a2 2 0 114 0 2 2 0 01-4 0z" />
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a4 4 0 00-3.446 6.032l-2.261 2.26a1 1 0 101.414 1.415l2.261-2.261A4 4 0 1011 5z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
