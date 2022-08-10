<x-app-layout title="Planeacion">
    <div class="container grid px-6 mx-auto">
        <form method="post" action="{{ route('planeacion.create') }}">
            @csrf
            <label class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                    Tipo de parte
                </span>
                <select id='SeTP' name='SeTP' onchange='PCenable()'
                    class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <option value=''>---Select---</option>
                    <option value='1'>Partes finales</option>
                    <option value='2'>Partes componentes</option>
                </select>
            </label>
            <label class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                    Codigo planeador
                </span>
                <select id='SePC' name='SePC'  onchange='WCenable()' disabled='true'
                    class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                    <option value=''>---Select---</option>
                    @foreach ($ipb as $PlannerC)
                        <option value='{{ $PlannerC->PBPBC }}'>{{ $PlannerC->PBPBC }}//{{ $PlannerC->PBNAM }}</option>
                    @endforeach
                </select>
            </label>
            <label class="block mt-4 text-sm">
                <span class="text-gray-700 dark:text-gray-400">
                    Centro de costos
                </span>
                <select id='SeWC' name='SeWC'
                    class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                    disabled='true'>
                    <option value=''>---Select---</option>
                    <option value='1'>Todos</option>
                    @foreach ($LWK as $WCss)
                        <option value={{ $WCss->WWRKC }}>{{ $WCss->WWRKC }}//{{ $WCss->WDESC }}</option>
                    @endforeach
                </select>
            </label>
            <div class="container grid px-6 mx-auto">
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
                document.getElementById("SeWC").disabled = false;
            } else {
                document.getElementById("SeWC").disabled = true;
            }
        };
    </script>
    <script>
        function PCenable() {
            if (this.value != '') {
                document.getElementById("SePC").disabled = false;
            } else {
                document.getElementById("SePC").disabled = true;
            }
        };
    </script>
</x-app-layout>
