<x-app-layout title="Forecast vs. Firm">
    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-center text-gray-700 dark:text-gray-200">
            Forecast vs. Firm
        </h2>

        <div class="flex flex-col flex-wrap justify-between mb-4 space-y-4 md:flex-row md:items-end md:space-x-4">
            <div>
                <a href="{{ route('forecast-vs-firm.report-pdf') }}" class="flex items-center justify-between px-10 py-4 font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red">
                    <span>Report PDF</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
                    </svg>
                </a>
            </div>
            <div>
                <a href="{{ route('forecast-vs-firm.report-excel') }}" class="flex items-center justify-between px-10 py-4 font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                    <span>Report Excel</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
