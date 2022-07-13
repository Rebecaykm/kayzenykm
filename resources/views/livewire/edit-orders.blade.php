<div>

    {{-- <button wire:click="$set('modal', true)"
        class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 -ml-1" viewBox="0 0 20 20" fill="currentColor">
            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
            <path fill-rule="evenodd"
                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                clip-rule="evenodd" />
        </svg>
        <span>Editar</span>
    </button class="btn btn-blue"> --}}

    <button wire:click="$set('modal')">
        Edit
    </button>
    <x-dialog-modal wire:model="modal">
        <x-slot name="title">

        </x-slot>
        <x-slot name="content">

        </x-slot>
        <x-slot name="footer">

        </x-slot>
    </x-dialog-modal>
</div>
