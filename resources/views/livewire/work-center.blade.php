<div>
    <div class="flex flex-row gap-x-4 justify-end justify-items-stretch p-2 rounded-lg">
        <label class="block text-sm ">
            <span class="text-gray-700 dark:text-gray-300 text-xs">Área</span>
            <select wire:model="departament" id="departament" name="departament" class="block w-60 text-xs dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray">
                <option></option>
                @foreach ($departaments as $departament)}
                <option value="{{ $departament->code }}">
                    {{ $departament->name }}
                </option>
                @endforeach
            </select>
        </label>
        <label class="block text-sm ">
            <span class="text-gray-700 dark:text-gray-300 text-xs">Centro de Trabajo</span>
            <select id="workCenter" name="workCenter" class="block w-60 text-xs dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray">
                <option></option>
                @foreach ($workCenters as $workCenter)
                <option value="{{ $workCenter->WWRKC }}">
                    {{ $workCenter->WWRKC }} - {{ $workCenter->WDESC }}
                </option>
                @endforeach
            </select>
        </label>
        <label class="block text-sm ">
            <span class="text-gray-700 dark:text-gray-300 text-xs">Fecha de Entrega</span>
            <input id="dueDate" name="dueDate" type="date" class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
        </label>
        <div class="flex justify-center py-2">
            <button type="submit" class="flex items-center justify-between px-4 py-2 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
                <span class="mr-2">Buscar</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 9a2 2 0 114 0 2 2 0 01-4 0z" />
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a4 4 0 00-3.446 6.032l-2.261 2.26a1 1 0 101.414 1.415l2.261-2.261A4 4 0 1011 5z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>
