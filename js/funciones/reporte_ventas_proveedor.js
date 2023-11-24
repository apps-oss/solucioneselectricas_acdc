$(document).ready(function () {
    $(".datepick").datepicker();
    $(".select2").select2();

    $("#submit").click(function () {
        var process = $("#process").val();

        if (process == "inventario") {
            let id_proveedor = $("#id_proveedor").val();
            let mes_inicial = $('#mes_inicial').val();
            let mes_final = $('#mes_final').val();

            var cadena = "reporte_ventas_proveedor.php?id_proveedor="
                + id_proveedor + "&mes_inicial="
                + mes_inicial + "&mes_final=" + mes_final;

            window.open(cadena, '', '');
        }
        else {
            cargar();
        }

    })
});
