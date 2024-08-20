<div>
    <div class="px-4 py-3 my-2 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <!-- Button with Loading State -->
        <x-button
            wire:click="refreshPartNumber"
            wire:loading.attr="disabled"
            wire:loading.class="bg-gray-500 cursor-not-allowed"
            class="flex items-center justify-center w-full bg-blue-500 hover:bg-blue-600">
            <!-- Default State -->
            <span wire:loading.remove>Actualizar No. de Parte</span>

            <!-- Loading State -->
            <span wire:loading.flex class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Actualizando No. de Parte...</span>
            </span>
        </x-button>
    </div>
</div>
