<x-app-layout title="Daily Production">
    <div class="container grid p-6 mx-auto">
        <h2 class="p-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Daily Production Panning
        </h2>
        <form method="GET" action="{{ route('daily-production.index') }}">
            <div class="flex flex-row gap-x-4 p-2 rounded-lg">
                <span class="text-gray-700 dark:text-gray-400 text-xs">Work Center : {{ $work }}</span>
                <span class="text-gray-700 dark:text-gray-400 text-xs">Due Date : {{ $date }}</span>
            </div>
            <div class="flex flex-row gap-x-4 justify-end p-2 rounded-lg">
                <label class="block text-sm ">
                    <span class="text-gray-700 dark:text-gray-400 text-xs">Work Center</span>
                    <select id="workCenter" name="workCenter"
                        class="block w-60 text-xs dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <option>Select a Work Center</option>
                        @foreach ($workCenters as $workCenter)
                            <option value="{{ $workCenter->WWRKC }}">
                                {{ $workCenter->WWRKC }} - {{ $workCenter->WDESC }}
                            </option>
                        @endforeach
                    </select>
                </label>
                <label class="block text-sm ">
                    <span class="text-gray-700 dark:text-gray-400 text-xs">Due Date</span>
                    <input id="dueDate" name="dueDate" type="date"
                        class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
                <div class="flex justify-center">
                    <button type="submit"
                        class="flex items-center justify-between px-4 py-2 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                        <span class="mr-2">Search</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 9a2 2 0 114 0 2 2 0 01-4 0z" />
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a4 4 0 00-3.446 6.032l-2.261 2.26a1 1 0 101.414 1.415l2.261-2.261A4 4 0 1011 5z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

            </div>
        </form>
        <div class="w-full overflow-hidden rounded-lg shadow-lg border-2 bg-white dark:bg-gray-800">
            <div class="w-full overflow-x-auto text-center h-96">
                @if ($dailyProdcution->count())
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                <th class="px-4 py-3">Shift</th>
                                <th class="px-4 py-3">No.</th>
                                <th class="px-4 py-3">Parts No.</th>
                                <th class="px-4 py-3">Cycle Time</th>
                                <th class="px-4 py-3">Shop Order</th>
                                <th class="px-4 py-3">Plan Quantity</th>
                                <th class="px-4 py-3">Plan Time (Min)</th>
                                <th class="px-4 py-3">Real Quantity</th>
                                <th class="px-4 py-3">Scrap Quantity</th>
                                <th class="px-4 py-3">Situation</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                            @foreach ($dailyProdcution as $daily)
                                <tr>
                                    <td class="px-2 py-2 text-xs">
                                        {{ $daily->SOCNO }}
                                    </td>
                                    <td class="px-2 py-2 text-xs">
                                        {{-- {{ $daily-> }} --}}
                                    </td>
                                    <td class="px-2 py-2 text-xs">
                                        {{ $daily->SPROD }}
                                    </td>
                                    <td class="px-2 py-2 text-xs">
                                        {{-- {{ $daily-> }} --}}
                                    </td>
                                    <td class="px-2 py-2 text-xs">
                                        {{ $daily->SORD }}
                                    </td>
                                    <td class="px-2 py-2 text-xs">
                                        {{ $daily->SQREQ }}
                                    </td>
                                    <td class="px-2 py-2 text-xs">
                                        {{-- {{ $daily-> }} --}}
                                    </td>
                                    <td class="px-2 py-2 text-xs">
                                        {{ $daily->SQFIN }}
                                    </td>
                                    <td class="px-2 py-2 text-xs">
                                        {{ $daily->SQREMM }}
                                    </td>
                                    <td class="px-2 py-2 text-xs">
                                        @if ($daily->SQREQ <= $daily->SQFIN)
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:text-white dark:bg-yellow-600">
                                                Finish
                                            </span>
                                        @elseif ($daily->SQREQ > $daily->SQFIN && $daily->SID == 'SZ')
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">
                                                Cancel
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                                In Process
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            @if ($dailyProdcution->count())
                <div
                    class="grid px-4 py-3 rounded-b-lg text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-100 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                    <span class="flex items-center col-span-3">
                        Show {{ $dailyProdcution->firstItem() }} - {{ $dailyProdcution->lastItem() }}
                    </span>
                    <span class="col-span-2"></span>
                    <!-- Pagination -->
                    <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                        <nav aria-label="Table navigation">
                            <ul class="inline-flex items-center">
                                {{ $dailyProdcution->withQueryString()->links() }}
                            </ul>
                        </nav>
                    </span>
                </div>
            @else
                <div
                    class="px-4 py-3 text-center rounded-md text-sm font-semibold text-gray-700 uppercase bg-gray-100 sm:grid-cols-9 dark:text-gray-500 dark:bg-gray-800">
                    No data to show
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
