<x-app-layout title="Raw Material">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            {{ __('Raw Material') }}
        </h2>

        <div class="px-4 py-3 bg-white rounded-lg shadow-md dark:bg-gray-800">

            <div class="py-3">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @elseif(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
                @endif
            </div>

            <form action="{{ route('raw-material.store') }}" method="post">
                @csrf
                <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">
                        {{ __('Pack') }}
                    </span>
                    <div class="relative text-gray-500 focus-within:text-purple-600">
                        <input name="pack" autofocus class="block w-full pr-20 mt-1 text-sm text-black border dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray form-input @error('order') border-red-600 @enderror" autocomplete="off" autofocus />
                        <button type="submit" class="absolute inset-y-0 right-0 px-4 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-r-md active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                            Guardar
                        </button>
                    </div>
                    @error('pack')
                    <span class="text-xs text-red-600 dark:text-red-400">
                        {{ $message }}
                    </span>
                    @enderror
                </label>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[name="pack"]').focus();
        });
    </script>
</x-app-layout>
