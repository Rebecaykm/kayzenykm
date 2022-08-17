<x-app-layout title="Daily Production">
    <div class="container grid px-6 mx-auto gap-y-2">
        <h2 class="p-2 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            Daily Production Planning
        </h2>
        <form method="GET" action="{{ route('daily-production.user') }}">
            <div class="flex flex-row gap-x-4 justify-end items-center p-2 rounded-lg">
                <label class="block text-sm ">
                    <span class="text-gray-700 dark:text-gray-400 text-xs">Work Center</span>
                    <select id="workCenter" name="workCenter"
                        class="block w-60 text-xs dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:focus:shadow-outline-gray">
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
                        class="block w-60 text-xs dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-gray form-input" />
                </label>
                <div class="flex justify-center">
                    <button type="submit"
                        class="flex items-center justify-between px-4 py-2 text-xs font-medium leading-5 text-white transition-colors duration-150 bg-blue-600 border border-transparent rounded-lg active:bg-blue-600 hover:bg-blue-700 focus:outline-none focus:shadow-outline-blue">
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
        <div class="w-full overflow-hidden rounded-lg shadow-xs border-2 bg-white dark:bg-gray-800">
            <div
                class="grid px-4 py-3 rounded-t-lg text-xs font-semibold tracking-wide text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 grid-cols-6 dark:text-gray-400 dark:bg-gray-800"">
                <div class="col-span-3 gap-x-4 flex flex-row">
                    <span class="flex items-center">
                        Work Center: {{ $work }}
                    </span>
                    <span class="flex items-center">
                        Due Date: {{ $date }}
                    </span>
                </div>
                <div class="col-span-3 flex justify-end">

                </div>
            </div>
            <div class="w-full overflow-x-auto text-center h-96">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800 sticky top-0">
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
                        @foreach ($dailyDiurnos as $key => $dailyDiurno)
                            <tr>
                                <td class="px-2 py-2 text-xs">
                                    D
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    {{ $num = $key + 1 }}
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    {{ $dailyDiurno->SPROD }}
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    {{-- {{ $dailyDiurno-> }} --}}
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input type="text"
                                            name="arrayDailyProductions[{{ $dailyDiurno->SORD }}][sord]" id="sord"
                                            value="{{ $dailyDiurno->SORD }}" hidden />
                                        {{ $dailyDiurno->SORD }}
                                    </label>
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    {{ $dailyDiurno->SQREQ }}
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    {{-- {{ $dailyDiurno-> }} --}}
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    {{ $dailyDiurno->SQFIN }}
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    {{ $dailyDiurno->SQREMM }}
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    @if ($dailyDiurno->SQREQ <= $dailyDiurno->SQFIN)
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:text-white dark:bg-yellow-600">
                                            Finish
                                        </span>
                                    @elseif ($dailyDiurno->SQREQ > $dailyDiurno->SQFIN && $dailyDiurno->SID == 'SZ')
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
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                        @foreach ($dailyNocturnos as $key => $dailyNocturno)
                            <tr>
                                <td class="px-2 py-2 text-xs">
                                    N
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    {{ $num = $num + 1 }}
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    {{ $dailyNocturno->SPROD }}
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    {{-- {{ $dailyNocturno-> }} --}}
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    <label class="flex items-center justify-center dark:text-gray-400">
                                        <input type="text"
                                            name="arrayDailyProductions[{{ $dailyNocturno->SORD }}][sord]"
                                            id="sord" value="{{ $dailyNocturno->SORD }}" hidden />
                                        {{ $dailyNocturno->SORD }}
                                    </label>
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    {{ $dailyNocturno->SQREQ }}
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    {{-- {{ $dailyNocturno-> }} --}}
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    {{ $dailyNocturno->SQFIN }}
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    {{ $dailyNocturno->SQREMM }}
                                </td>
                                <td class="px-2 py-2 text-xs">
                                    @if ($dailyNocturno->SQREQ <= $dailyNocturno->SQFIN)
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:text-white dark:bg-yellow-600">
                                            Finish
                                        </span>
                                    @elseif ($dailyNocturno->SQREQ > $dailyNocturno->SQFIN && $dailyNocturno->SID == 'SZ')
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
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div
                class="grid px-4 py-3 rounded-b-lg text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-100 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                <span class="flex items-center col-span-3">
                    {{-- Show {{ $dailyProdcution->firstItem() }} - {{ $dailyProdcution->lastItem() }} --}}
                </span>
                <span class="col-span-2"></span>
                <!-- Pagination -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav aria-label="Table navigation">
                        <ul class="inline-flex items-center">
                            {{-- {{ $dailyProdcution->withQueryString()->links() }} --}}
                        </ul>
                    </nav>
                </span>
            </div>

            {{-- <div
                class="px-4 py-3 text-center rounded-md text-sm font-semibold text-gray-700 uppercase bg-gray-100 sm:grid-cols-9 dark:text-gray-500 dark:bg-gray-800">
                No data to show
            </div> --}}

        </div>
    </div>
</x-app-layout>
