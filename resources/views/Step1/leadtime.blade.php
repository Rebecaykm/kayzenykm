<x-app-layout title="Tables">

    <div class="container grid px-6 mx-auto">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
          Desplazamientos de produccion <br>
          {{$F1 }}
        </h2>

        <h4 class="mb-4 text-lg font-semibold text-gray-600 dark:text-gray-300">
            Table with actions
        </h4>
        @if (session('mensaje'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('mensaje') }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none';">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/></svg>
            </span>
        </div>
    @endif
        <div class="w-full overflow-hidden rounded-lg shadow-xs">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                            <th class="px-4 py-3">Numero de parte </th>
                            <th class="px-4 py-3">Clase</th>
                            <th class="px-4 py-3">Padre</th>
                            <th class="px-4 py-3">Offset</th>
                            <th class="px-4 py-3">Cambio</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">

                        @foreach ($partes as $item)
                            <tr class="text-gray-700 dark:text-gray-400">
                                <td class="px-4 py-3">
                                    <p class="font-semibold"> {{ $item->MCCPRO }}</p>
                                </td>

                                <td class="px-4 py-3 text-sm">
                                    <p class="font-semibold">{{ $item->MCCCLS }}
                                    </p>
                                </td>

                                <td class="px-4 py-3 text-sm">
                                    <p class="font-semibold">{!! implode('<br>', $item->padres) !!}
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <p class="font-semibold">{{  floor($item->LTLDTM)?? 0}} Turno(s)
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-xs">

                                    {{-- @livewire('alta-ymltm', ['parte' => $item->MCCPRO]) --}}
                                    <form method="post" action="{{ route('offset.create') }}">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="cantidad" class="block font-semibold">Desplazamiento por
                                                turno</label>
                                            <input type="hidden" value='{{ $F1 }}' name='F1'
                                                id="F1" class="border p-2 w-full">
                                            <input type="hidden" value='{{ $item->MCCPRO }}' name='item'
                                                id="item" class="border p-2 w-full">
                                            <input type="number"name='cantidad' id="cantidad"
                                                class="border p-2 w-full" required>
                                        </div>
                                        <button type="submit"
                                            class="bg-blue-500 text-white p-2 rounded">Guardar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</x-app-layout>
