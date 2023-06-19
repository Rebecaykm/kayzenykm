<x-app-layout title="Planeacion">
    <div class="container grid px-6 mx-auto">
        <form method="post" action="{{ route('planeacion.create') }}">
            @csrf
            <label class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                    Projecto
                </span>
                <select id='SeProject' name='SeProject' onchange='PCenable()'
                    class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <option value=''>---Select---</option>
                    @foreach ($LWK as $Projec)
                        <option value={{ $Projec->CCCODE }}>{{ $Projec->CCCODE }}//{{ $Projec->CCDESC }}</option>
                    @endforeach
                </select>
            </label>
            <div class="mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                  Tipo de planeación
                </span>
                <div class="mt-2">
                    <label class="inline-flex items-center text-gray-600 dark:text-gray-400">
                        <input type="radio" value='1' class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" name="Planeacion"  />
                        <span class="ml-2">Parte final </span>
                    </label>
                    <label class="inline-flex items-center ml-6 text-gray-600 dark:text-gray-400">
                        <input type="radio"  value="2" class="text-purple-600 form-radio focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray" name="Planeacion"  />
                        <span class="ml-2">Subcomponentes</span>
                    </label>
                </div>
            </div>

            <div class="container grid px-6 mx-auto mt-3 ">
                <button
                    type='submit'class="px-10 py-4 font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Procesar
                </button>
            </div>
        </form>
    </div>


    <script>
        function WCenable() {
            if (this.value != '') {
                document.getElementById("SeProject").value = '';
            }
        };
    </script>
    <script>
        function PCenable() {
            if (this.value != '') {
                document.getElementById("SePC").value = '';
            }
        };
    </script>
</x-app-layout>
