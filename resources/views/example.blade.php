<!-- <a href="{{route('examples')}}" target="_blank"> click me to pdf </a> -->

<button onclick="imprimirPDF()">Imprimir PDF</button>

<script>
    function imprimirPDF() {
        // Abrir una nueva ventana para la impresión
        var ventanaImpresion = window.open('{{ route("examples") }}', '_blank');

        // Esperar a que la ventana se cargue completamente antes de imprimir
        ventanaImpresion.onload = function() {
            ventanaImpresion.print();
            ventanaImpresion.onafterprint = function() {
                ventanaImpresion.close();
            };
        };
    }
</script>
