<x-app-layout title="Edit Open Order">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-4 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Editar Orden Abierta
        </h2>

        <div class="px-4 py-4 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
            <form method="POST" action="{{ route('open-orders.store', ['swrkc' => {{ $openOrder->SWRKC }}, 'sddte' => {{ $openOrder->SDDTE }}, 'sord' => {{ $openOrder->SORD }}, 'sprod' => {{ $openOrder->SPROD }}, 'sqreq' => {{ $openOrder->SQREQ }}, 'sqfin' => {{ $openOrder->SQFIN }}])}}">
                @csrf
                <div class="grid gap-6 grid-cols-2 grid-rows-3 flex items-center">
                    <label for="" class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Work Center</span>
                        <input type="text" name="work-center" id="work-center" value="{{ $openOrder->SWRKC }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" disabled>
                    </label>
                    <label for="" class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Due Date</span>
                        <input type="text" name="due-date" id="due-date" value="{{ $openOrder->SDDTE }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" disabled>
                    </label>
                    <label for="" class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Shop Order Number</span>
                        <input type="text" name="order-number" id="order-number" value="{{ $openOrder->SORD }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" disabled>
                    </label>
                    <label for="" class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Item Number</span>
                        <input type="text" name="item-number" id="item-number" value="{{ $openOrder->SPROD }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" disabled>
                    </label>
                    <label for="" class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Quantity Required</span>
                        <input type="text" name="quantity-requerid" id="quantity-requerid" value="{{ $openOrder->SQREQ }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" disabled>
                    </label>
                    <label for="" class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Quantity Finished</span>
                        <input type="text" name="quantity-finished" id="quantity-finished" value="{{ $openOrder->SQFIN }}" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" disabled>
                    </label>
                    <label for="" class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400">Due Date</span>
                        <input type="date" name="update" id="updtae" class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">
                    </label>
                    <label for="" class="block text-sm">
                        <input type="checkbox" name="cancel" id="cancel" class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" />
                        <span class="ml-2">
                            Cancel Shop Order
                        </span>
                    </label>
                </div>
                <div class="flex justify-end m-2">
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

