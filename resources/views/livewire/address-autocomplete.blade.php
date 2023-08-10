<form method="post" action="{{ route('planeacion.buscar') }}" class="space-y-8 divide-y divide-gray-200"
    x-data='{
    addressSelected(e) {
        let value = e.target.value
        let id = document.body.querySelector("datalist [value=\""+value+"\"]").dataset.value

        // todo: Do something interesting with this
        console.log(id);
    }
}'>
    @csrf
    <div class="flex flex-row gap-x-4 justify-center items-center p-2 rounded-lg">


        <label class="block mt-4 text-sm">
            <span class="text-gray-700 dark:text-gray-400 text-xs">Numero de parte </span>
            <input id='item' name='item' type="text" list="streetAddressOptions" wire:model="streetAddress"
                class="form-control block w-80 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input
        fancy-tailwind-things"
                x-on:change.debounce="addressSelected($event)">
        </label>

        <datalist id="streetAddressOptions">
            @foreach ($searchResults as $result)
                <option wire:key="{{ $result['IPROD'] }}" data-value="{{ $result['IPROD'] }}"
                    value="{{ $result['IPROD'] }}"></option>
            @endforeach
        </datalist>
        <label class="block mt-4 text-sm ">
            <span class="text-gray-700 dark:text-gray-400 text-xs">Fecha inicial</span>
            <input id="fecha" name="fecha" type="date"
                class="block w-30 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                required />
        </label>
        <label class="block mt-4 text-sm pt-6 ">
            <input type="radio" value='1'
                class="text-purple-600 form-radio focus:border-purple-400  focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                id='Type' name="Type" />
            <span class="ml-2">Final</span>
        </label>
        <label class="block mt-4 text-sm pt-6 ">
            <input type="radio" id='Type' name="Type"
            value='2'
                class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                id='Type' name="Type" />
            <span class="ml-2">Subcomponentes</span>
        </label>
        <button type="submit" 
            class=" flex items-center justify-between px-4 pt-6 py-2 mt-5 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
            <span class="mr-2">Search</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M9 9a2 2 0 114 0 2 2 0 01-4 0z" />
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a4 4 0 00-3.446 6.032l-2.261 2.26a1 1 0 101.414 1.415l2.261-2.261A4 4 0 1011 5z"
                    clip-rule="evenodd" />
            </svg>
        </button>

    </div>





</form>
