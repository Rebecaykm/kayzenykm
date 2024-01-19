<!-- resources/views/tree.blade.php -->

<x-app-layout title="Dashboard">
    <div class="container mx-auto p-6">
        <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
            B O M
        </h2>

        <form action="{{ url('tree') }}" method="get" class="mb-4">
            <label for="search" class="mr-2">Buscar: </label>
            <input type="text" id="search" name="search" placeholder="Ingrese el número de parte" autocomplete="off" class="p-2 border border-gray-300 rounded-md">
            <button type="submit" class="p-2 bg-blue-500 text-white rounded-md"> Buscar </button>
        </form>

        <ul class="list-none">
            @foreach ($tree as $node)
                @include('treeNode', ['node' => $node])
                <hr>
            @endforeach
        </ul>
    </div>
</x-app-layout>
